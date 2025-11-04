<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidature;
use App\Models\User;
use App\Models\Classe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\CandidatureStatusUpdated;

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
        return view('admin.candidatures.show', compact('candidature'));
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
            $user = User::create([
                'name' => trim($request->prenom . ' ' . $request->nom),
                'email' => $request->email,
                'password' => bcrypt(str()->random(12)),
                'role' => 'candidat',
            ]);
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

        $candidature->update(['statut' => $request->statut]);

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
}

 
