<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidature;
use App\Models\User;
use App\Models\Classe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\CandidatureStatusUpdated;
use App\Mail\CandidatureReminder;
use App\Mail\CandidatureAccountCredentials;
use Illuminate\Support\Facades\Storage;
use App\Models\Notification;
use App\Mail\NotificationMail;

class CandidatureController extends Controller
{
    public function index()
    {
        $candidatures = Candidature::with('user')->orderByDesc('created_at')->paginate(15);
        $classes = Classe::orderBy('nom')->get();
        return view('admin.candidatures.index', compact('candidatures', 'classes'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('admin.candidatures.create', compact('users'));
    }

    public function show(Candidature $candidature)
    {
        $candidature->load(['user', 'filiere', 'specialite', 'classe']);
        $classes = Classe::orderBy('nom')->get();
        return view('admin.candidatures.show', compact('candidature', 'classes'));
    }

    public function edit(Candidature $candidature)
    {
        $classes = Classe::orderBy('nom')->get();
        return view('admin.candidatures.edit', compact('candidature', 'classes'));
    }

    public function update(Request $request, Candidature $candidature)
    {
        $data = $request->validate([
            'statut' => 'required|in:soumis,verifie,admis,rejete',
            'evaluation_date' => 'nullable|date',
            'commentaire_admin' => 'nullable|string',
            'filiere_id' => 'nullable|exists:filieres,id',
            'specialite_id' => 'nullable|exists:specialites,id',
            'classe_id' => 'nullable|exists:classes,id',
            'phone' => 'nullable|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'doc_identite' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'doc_diplome' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'doc_releve' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'doc_lettre' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'doc_photo' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'doc_cv' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'motivation_text' => 'nullable|string',
        ]);

        $candidature->update([
            'statut' => $data['statut'],
            'evaluation_date' => $data['evaluation_date'] ?? null,
            'commentaire_admin' => $data['commentaire_admin'] ?? null,
            'filiere_id' => $data['filiere_id'] ?? null,
            'specialite_id' => $data['specialite_id'] ?? null,
            'classe_id' => $data['classe_id'] ?? null,
        ]);

        // Mettre à jour les infos utilisateur
        $userData = [];
        if (isset($data['phone'])) $userData['phone'] = $data['phone'];
        if (isset($data['contact_name'])) $userData['contact_name'] = $data['contact_name'];
        if (isset($data['contact_phone'])) $userData['contact_phone'] = $data['contact_phone'];
        if (!empty($data['classe_id']) && $data['statut'] === 'admis') {
            $userData['classe_id'] = $data['classe_id'];
            $userData['role'] = 'etudiant';
        }
        if (!empty($userData)) {
            $candidature->user->update($userData);
        }

        // Photo de profil
        if ($request->hasFile('profile_photo')) {
            $pp = $request->file('profile_photo');
            $ppName = 'profile-' . $candidature->user->id . '-' . time() . '.' . $pp->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('profiles', $pp, $ppName);
            $candidature->user->update(['profile_photo' => 'profiles/' . $ppName]);
        }

        $docs = $candidature->documents ? json_decode($candidature->documents, true) : [];
        // Préparer les statuts existants pour les mettre à jour uniquement pour les docs modifiés
        $docStatuses = $candidature->document_statuses ?: [];
        $labels = [
            'doc_identite' => "Pièce d'identité",
            'doc_diplome' => 'Diplôme',
            'doc_releve' => 'Relevé de notes',
            'doc_lettre' => 'Lettre de motivation',
            'doc_photo' => 'Photo',
            'doc_cv' => 'CV',
        ];
        foreach ($labels as $field => $label) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = 'cand-' . time() . '-' . $field . '-' . $file->getClientOriginalName();
                \Illuminate\Support\Facades\Storage::disk('public')->putFileAs('candidatures', $file, $filename);
                // remove existing with same key
                $docs = collect($docs)->reject(fn($d) => ($d['key'] ?? '') === $field)->values()->all();
                $docs[] = [ 'key' => $field, 'label' => $label, 'path' => 'candidatures/' . $filename, 'name' => $file->getClientOriginalName() ];
                // Reset du statut du document à "en_attente"
                $docStatuses[$field] = [
                    'status' => 'en_attente',
                    'note' => null,
                    'updated_by' => auth()->id(),
                    'updated_at' => now()->toDateTimeString(),
                ];
            }
        }

        // Gérer la lettre de motivation (texte)
        if ($request->filled('motivation_text') && !$request->hasFile('doc_lettre')) {
            $html = '<h2>Lettre de motivation</h2><p style="white-space:pre-wrap; font-family: DejaVu Sans, Arial;">' . e($request->motivation_text) . '</p>';
            $pdfPath = 'candidatures/cand-' . time() . '-lettre.pdf';
            if (class_exists('Barryvdh\\DomPDF\\Facade\\Pdf')) {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
                Storage::disk('public')->put($pdfPath, $pdf->output());
            } else {
                Storage::disk('public')->put($pdfPath, $html);
            }
            // remove existing lettre
            $docs = collect($docs)->reject(fn($d) => ($d['key'] ?? '') === 'doc_lettre')->values()->all();
            $docs[] = [ 
                'key' => 'doc_lettre', 
                'label' => 'Lettre de motivation', 
                'path' => $pdfPath, 
                'name' => 'lettre.pdf',
                'text' => $request->motivation_text
            ];
        }

        // Si une photo de profil existe, marquer doc_photo comme conforme
        if (!empty($candidature->user->profile_photo)) {
            $docStatuses['doc_photo'] = [
                'status' => 'conforme',
                'note' => null,
                'updated_by' => auth()->id(),
                'updated_at' => now()->toDateTimeString(),
            ];
        }

        $candidature->update([
            'documents' => $docs ? json_encode($docs) : null,
            'document_statuses' => $docStatuses ?: null,
        ]);

        return redirect()->route('admin.candidatures.show', $candidature)->with('success', 'Candidature mise à jour.');
    }

    public function store(Request $request)
    {
        $mode = $request->input('user_mode', 'existing');

        if ($mode === 'new') {
            $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
            ]);
            $tempPassword = str()->password(12);
            $user = User::create([
                'name' => trim($request->prenom . ' ' . $request->nom),
                'email' => $request->email,
                'password' => bcrypt($tempPassword),
                'role' => 'candidat',
            ]);
            // Envoyer les identifiants de connexion
            Mail::to($user->email)->send(new CandidatureAccountCredentials($user->name, $user->email, $tempPassword));
        } else {
            $request->validate(['user_id' => 'required|exists:users,id']);
            $user = User::find($request->user_id);
        }

        $validated = $request->validate([
            'statut' => 'required|in:soumis,verifie,admis,rejete',
            'commentaire_admin' => 'nullable|string',
            'doc_identite' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'doc_diplome' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'doc_releve' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'doc_lettre' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'doc_photo' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'doc_cv' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Upload documents (nommés)
        $docFields = [
            'doc_identite' => 'Pièce d\'identité',
            'doc_diplome' => 'Diplôme',
            'doc_releve' => 'Relevé de notes',
            'doc_lettre' => 'Lettre de motivation',
            'doc_photo' => 'Photo',
            'doc_cv' => 'CV',
        ];
        $savedDocs = [];
        foreach ($docFields as $field => $label) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = 'cand-' . time() . '-' . $field . '-' . $file->getClientOriginalName();
                \Illuminate\Support\Facades\Storage::disk('public')->putFileAs('candidatures', $file, $filename);
                $savedDocs[] = [
                    'key' => $field,
                    'label' => $label,
                    'path' => 'candidatures/' . $filename,
                    'name' => $file->getClientOriginalName(),
                ];
            }
        }

        $candidature = Candidature::create([
            'user_id' => $user->id,
            'statut' => $validated['statut'],
            'documents' => $savedDocs ? json_encode($savedDocs) : null,
            'commentaire_admin' => $validated['commentaire_admin'] ?? null,
        ]);

        // Email notification initiale
        Mail::to($candidature->user->email)->send(new CandidatureStatusUpdated($candidature));

        return redirect()->route('admin.candidatures.index')->with('success', 'Candidature créée.');
    }

    public function updateStatus(Request $request, Candidature $candidature)
    {
        $request->validate([
            'statut' => 'required|in:soumis,verifie,admis,rejete',
        ]);

        $data = ['statut' => $request->statut];
        if ($request->statut === 'verifie') {
            $data['verified_by'] = auth()->id();
        }
        if (in_array($request->statut, ['admis', 'rejete'], true)) {
            $data['decided_by'] = auth()->id();
        }
        $candidature->update($data);

        // Email notification
        Mail::to($candidature->user->email)->send(new CandidatureStatusUpdated($candidature));

        return back()->with('success', 'Statut mis à jour et email envoyé.');
    }

    public function updateDocumentStatus(Request $request, Candidature $candidature)
    {
        $data = $request->validate([
            'key' => 'required|string',
            'status' => 'required|in:conforme,non_conforme',
            'note' => 'nullable|string|max:1000',
        ]);
        $statuses = $candidature->document_statuses ?: [];
        $statuses[$data['key']] = [
            'status' => $data['status'],
            'note' => $data['note'] ?? null,
            'updated_by' => auth()->id(),
            'updated_at' => now()->toDateTimeString(),
        ];

        // Unifier: si profile_photo existe, considérer doc_photo conforme
        if ($data['key'] === 'doc_photo' && ($candidature->user->profile_photo ?? null)) {
            $statuses['doc_photo']['status'] = 'conforme';
        }

        $candidature->update(['document_statuses' => $statuses]);

        // Email + notification au candidat si non conforme
        if ($data['status'] === 'non_conforme') {
            // Chercher le libellé du document
            $labels = [
                'doc_identite' => "Pièce d'identité",
                'doc_diplome' => 'Diplôme',
                'doc_releve' => 'Relevé de notes',
                'doc_lettre' => 'Lettre de motivation',
                'doc_photo' => 'Photo',
                'doc_cv' => 'CV',
            ];
            $label = $labels[$data['key']] ?? $data['key'];

            // Créer une notification et tenter un email
            $notification = Notification::create([
                'titre' => 'Document non conforme: ' . $label,
                'contenu' => ($data['note'] ? ('Raison: ' . $data['note'] . '. ') : '') . 'Veuillez remplacer ce document sur votre espace. #' . $candidature->id,
                'type' => 'warning',
                'destinataire_type' => 'user',
                'user_id' => $candidature->user_id,
                'sender_id' => auth()->id(),
                'envoye_email' => false,
            ]);
            try {
                if (class_exists(\App\Mail\NotificationMail::class)) {
                    Mail::to($candidature->user->email)->send(new NotificationMail($notification));
                    $notification->update(['envoye_email' => true]);
                }
            } catch (\Throwable $e) {
                \Log::error('Echec email document non conforme: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Statut du document mis à jour.');
    }

    public function schedule(Request $request, Candidature $candidature)
    {
        $request->validate([
            'evaluation_date' => 'required|date',
        ]);
        $candidature->update(['evaluation_date' => $request->evaluation_date]);

        // Informer par email
        Mail::to($candidature->user->email)->send(new CandidatureStatusUpdated($candidature));

        return back()->with('success', "Date d'évaluation planifiée et email envoyé.");
    }

    public function markEvaluated(Candidature $candidature)
    {
        $candidature->update(['evaluated_by' => auth()->id()]);
        return back()->with('success', "Évaluation validée.");
    }

    public function markInscriptionPaid(Candidature $candidature)
    {
        $candidature->update([
            'inscription_paid' => true,
            'inscription_paid_by' => auth()->id(),
            'inscription_paid_at' => now(),
        ]);
        return back()->with('success', "Frais d'inscription marqués comme payés.");
    }

    public function remind(Candidature $candidature)
    {
        $required = [
            'doc_identite' => "Pièce d'identité",
            'doc_diplome' => 'Diplôme',
            'doc_releve' => 'Relevé de notes',
            'doc_lettre' => 'Lettre de motivation',
            'doc_photo' => 'Photo',
            // 'doc_cv' optionnel
        ];
        $docs = $candidature->documents ? json_decode($candidature->documents, true) : [];
        $presentKeys = collect($docs)->pluck('key')->filter()->values()->all();
        $missing = collect($required)->reject(function ($label, $key) use ($presentKeys) {
            return in_array($key, $presentKeys, true);
        })->toArray();

        Mail::to($candidature->user->email)->send(new CandidatureReminder($candidature, $missing));
        return back()->with('success', "Email de rappel envoyé au candidat.");
    }

    public function assignClass(Request $request, Candidature $candidature)
    {
        $data = $request->validate([
            'classe_id' => 'required|exists:classes,id',
        ]);
        $candidature->user->update([
            'classe_id' => $data['classe_id'],
            'role' => 'etudiant',
        ]);
        return back()->with('success', 'Classe associée au candidat et rôle mis à jour.');
    }

    public function destroy(Candidature $candidature)
    {
        // Supprimer les fichiers de documents s'ils existent
        $docs = $candidature->documents ? json_decode($candidature->documents, true) : [];
        foreach ($docs as $doc) {
            if (!empty($doc['path']) && Storage::disk('public')->exists($doc['path'])) {
                Storage::disk('public')->delete($doc['path']);
            }
        }
        $candidature->delete();
        return redirect()->route('admin.candidatures.index')->with('success', 'Candidature supprimée.');
    }
}

 
