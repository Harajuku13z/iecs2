<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidature;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\CandidatureStatusUpdated;

class CandidatureController extends Controller
{
    public function index()
    {
        $candidatures = Candidature::with('user')->orderByDesc('created_at')->paginate(15);
        return view('admin.candidatures.index', compact('candidatures'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('admin.candidatures.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'statut' => 'required|in:soumis,verifie,admis,rejete',
            'commentaire_admin' => 'nullable|string',
        ]);

        $candidature = Candidature::create($validated);

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
}

 
