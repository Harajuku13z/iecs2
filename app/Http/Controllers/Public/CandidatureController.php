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

        // Générer un PDF pour la lettre si saisie
        if (!$request->hasFile('doc_lettre') && $request->filled('motivation_text')) {
            $html = '<h2>Lettre de motivation</h2><p style="white-space:pre-wrap; font-family: DejaVu Sans, Arial;">' . e($request->motivation_text) . '</p>';
            $pdfPath = 'candidatures/cand-' . time() . '-lettre.pdf';
            if (class_exists('Barryvdh\\DomPDF\\Facade\\Pdf')) {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
                Storage::disk('public')->put($pdfPath, $pdf->output());
            } else {
                Storage::disk('public')->put($pdfPath, $html);
            }
            $savedDocs[] = [ 'key' => 'doc_lettre', 'label' => 'Lettre de motivation', 'path' => $pdfPath, 'name' => 'lettre.pdf' ];
        }

        // Mettre à jour les infos utilisateur
        $user->update([
            'phone' => $request->phone,
            'contact_name' => $request->contact_name,
            'contact_phone' => $request->contact_phone,
        ]);
        if ($request->hasFile('profile_photo')) {
            $pp = $request->file('profile_photo');
            $ppName = 'profile-' . $user->id . '-' . time() . '.' . $pp->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('profiles', $pp, $ppName);
            $user->update(['profile_photo' => 'profiles/' . $ppName]);
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


