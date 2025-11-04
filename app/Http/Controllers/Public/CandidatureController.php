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
        $request->validate([
            'doc_identite' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'doc_diplome' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'doc_releve' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'doc_lettre' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'doc_photo' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'doc_cv' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $labels = [
            'doc_identite' => "Pièce d'identité",
            'doc_diplome' => 'Diplôme',
            'doc_releve' => 'Relevé de notes',
            'doc_lettre' => 'Lettre de motivation',
            'doc_photo' => 'Photo',
            'doc_cv' => 'CV',
        ];

        $savedDocs = [];
        foreach ($labels as $field => $label) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = 'cand-' . time() . '-' . $field . '-' . $file->getClientOriginalName();
                Storage::disk('public')->putFileAs('candidatures', $file, $filename);
                $savedDocs[] = [ 'key' => $field, 'label' => $label, 'path' => 'candidatures/' . $filename, 'name' => $file->getClientOriginalName() ];
            }
        }

        $candidature = Candidature::updateOrCreate(
            ['user_id' => $user->id],
            ['statut' => 'soumis', 'documents' => $savedDocs ? json_encode($savedDocs) : null]
        );

        return redirect()->route('etudiant.dashboard')->with('success', 'Candidature soumise.');
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


