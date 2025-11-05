<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Candidature;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\CandidatureAccountCredentials;
use App\Models\Notification;

class CandidatureController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $candidature = Candidature::where('user_id', $user->id)->first();
        return view('public.candidature.create', compact('candidature'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $candidature = Candidature::where('user_id', $user->id)->first();
        
        // Récupérer les documents existants si la candidature existe
        $existingDocs = [];
        if ($candidature && $candidature->documents) {
            $existingDocsRaw = $candidature->documents;
            $existingDocs = is_array($existingDocsRaw) ? $existingDocsRaw : (json_decode($existingDocsRaw, true) ?: []);
        }
        
        $request->validate([
            'doc_identite' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'doc_diplome' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'doc_releve' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'doc_lettre' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'doc_cv' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'phone' => 'nullable|string|max:50',
            'contact_name' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'profile_photo' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'motivation_text' => 'nullable|string',
        ]);

        $labels = [
            'doc_identite' => "Pièce d'identité",
            'doc_diplome' => 'Diplôme',
            'doc_releve' => 'Relevé de notes',
            'doc_lettre' => 'Lettre de motivation',
            'doc_cv' => 'CV',
        ];

        $newDocs = [];
        foreach ($labels as $field => $label) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = 'cand-' . time() . '-' . $field . '-' . $file->getClientOriginalName();
                Storage::disk('public')->putFileAs('candidatures', $file, $filename);
                $newDocs[] = [ 'key' => $field, 'label' => $label, 'path' => 'candidatures/' . $filename, 'name' => $file->getClientOriginalName() ];
            }
        }

        // Générer un PDF pour la lettre si saisie et stocker aussi le texte
        if (!$request->hasFile('doc_lettre') && $request->filled('motivation_text')) {
            $html = '<h2>Lettre de motivation</h2><p style="white-space:pre-wrap; font-family: DejaVu Sans, Arial;">' . e($request->motivation_text) . '</p>';
            $pdfPath = 'candidatures/cand-' . time() . '-lettre.pdf';
            if (class_exists('Barryvdh\\DomPDF\\Facade\\Pdf')) {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
                Storage::disk('public')->put($pdfPath, $pdf->output());
            } else {
                Storage::disk('public')->put($pdfPath, $html);
            }
            $newDocs[] = [ 
                'key' => 'doc_lettre', 
                'label' => 'Lettre de motivation', 
                'path' => $pdfPath, 
                'name' => 'lettre.pdf',
                'text' => $request->motivation_text // Stocker aussi le texte original
            ];
        }

        // Fusionner les documents: garder les existants sauf ceux remplacés par les nouveaux
        $finalDocs = collect($existingDocs)->keyBy('key')->all();
        foreach ($newDocs as $newDoc) {
            $key = $newDoc['key'] ?? null;
            if ($key) {
                $finalDocs[$key] = $newDoc; // Remplace ou ajoute le nouveau document
            }
        }
        $finalDocsArray = array_values($finalDocs);

        // Mettre à jour les infos utilisateur (uniquement les champs fournis)
        $userUpdates = [];
        if ($request->filled('phone')) {
            $userUpdates['phone'] = $request->phone;
        }
        if ($request->filled('contact_name')) {
            $userUpdates['contact_name'] = $request->contact_name;
        }
        if ($request->filled('contact_phone')) {
            $userUpdates['contact_phone'] = $request->contact_phone;
        }
        if (!empty($userUpdates)) {
            $user->update($userUpdates);
        }
        if ($request->hasFile('profile_photo')) {
            $pp = $request->file('profile_photo');
            $ppName = 'profile-' . $user->id . '-' . time() . '.' . $pp->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('profiles', $pp, $ppName);
            $user->update(['profile_photo' => 'profiles/' . $ppName]);
        }

        // Déterminer la classe automatiquement si elle n'est pas fournie
        $classeId = $request->input('classe_id');
        if (!$classeId && $request->input('filiere_id') && $request->input('niveau_id')) {
            $classe = \App\Models\Classe::where('filiere_id', $request->input('filiere_id'))
                ->where('niveau_id', $request->input('niveau_id'))
                ->first();
            if ($classe) {
                $classeId = $classe->id;
            }
        }

        // Préparer les données de mise à jour de la candidature (uniquement les champs fournis)
        $candidatureData = [];
        if (!empty($newDocs)) {
            // Seulement mettre à jour les documents si de nouveaux ont été uploadés
            $candidatureData['documents'] = json_encode($finalDocsArray);
        }
        
        if ($request->filled('filiere_id')) {
            $candidatureData['filiere_id'] = $request->input('filiere_id');
        }
        if ($request->filled('specialite_id')) {
            $candidatureData['specialite_id'] = $request->input('specialite_id');
        }
        if ($request->filled('classe_id') || $classeId) {
            $candidatureData['classe_id'] = $classeId ?: $request->input('classe_id');
        }
        
        if (!$candidature) {
            // Créer une nouvelle candidature
            $candidatureData['user_id'] = $user->id;
            $candidatureData['statut'] = 'soumis';
            $candidature = Candidature::create($candidatureData);
        } elseif (!empty($candidatureData)) {
            // Mettre à jour uniquement les champs modifiés
            $candidature->update($candidatureData);
        }

        // Mettre à jour les statuts des documents (uniquement pour les nouveaux uploads)
        $statuses = $candidature->document_statuses ?: [];
        if (!empty($newDocs)) {
            foreach ($newDocs as $doc) {
                $key = $doc['key'] ?? null;
                if (!$key) continue;
                $statuses[$key] = [
                    'status' => 'en_attente',
                    'note' => null,
                    'updated_by' => $user->id,
                    'updated_at' => now()->toDateTimeString(),
                ];
            }
            $candidature->update(['document_statuses' => $statuses]);
        }
        // Si la photo de profil existe, considérer doc_photo comme conforme
        if (!empty($user->profile_photo)) {
            $statuses = $candidature->document_statuses ?: [];
            $statuses['doc_photo'] = [
                'status' => 'conforme',
                'note' => null,
                'updated_by' => $user->id,
                'updated_at' => now()->toDateTimeString(),
            ];
            $candidature->update(['document_statuses' => $statuses]);
        }

        // Notifier les administrateurs qu'un candidat a ajouté/modifié son dossier
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $notification = Notification::create([
                'titre' => 'Candidature mise à jour: ' . $user->name,
                'contenu' => 'Le candidat ' . $user->name . ' a déposé ou modifié des éléments de son dossier de candidature. #'.$candidature->id,
                'type' => 'info',
                'destinataire_type' => 'user',
                'user_id' => $admin->id,
                'sender_id' => $user->id,
            ]);
            try {
                if (class_exists('App\\Mail\\NotificationMail')) {
                    \Mail::to($admin->email)->send(new \App\Mail\NotificationMail($notification));
                }
            } catch (\Throwable $e) {
                \Log::error('Echec envoi email admin pour mise à jour candidature: '.$e->getMessage());
            }
        }

        return redirect()->route('etudiant.dashboard')->with('success', 'Votre dossier a été enregistré. Notre équipe va l’examiner.');
    }

    // Démarrer une candidature sans compte: crée un compte minimal et connecte l'utilisateur
    public function start(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
        ]);
        $tempPassword = str()->password(12);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($tempPassword),
            'role' => 'candidat',
        ]);
        Auth::login($user);
        Mail::to($user->email)->send(new CandidatureAccountCredentials($user->name, $user->email, $tempPassword));
        return redirect()->route('candidature.create')->with('success', 'Compte généré, vous pouvez déposer votre candidature.');
    }
}


