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
            'classe_id' => 'nullable|exists:classes,id',
            'doc_identite' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'doc_diplome' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'doc_releve' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'doc_lettre' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'doc_photo' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'doc_cv' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $candidature->update([
            'statut' => $data['statut'],
            'evaluation_date' => $data['evaluation_date'] ?? null,
            'commentaire_admin' => $data['commentaire_admin'] ?? null,
        ]);

        if (!empty($data['classe_id']) && $data['statut'] === 'admis') {
            $candidature->user->update(['classe_id' => $data['classe_id'], 'role' => 'etudiant']);
        }

        $docs = $candidature->documents ? json_decode($candidature->documents, true) : [];
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
            }
        }
        $candidature->update(['documents' => $docs ? json_encode($docs) : null]);

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

 
