<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Filiere;
use App\Models\Niveau;
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    public function index()
    {
        $classes = Classe::with(['filiere', 'niveau'])->paginate(15);
        return view('admin.classes.index', compact('classes'));
    }

    public function create()
    {
        $filieres = Filiere::all();
        $niveaux = Niveau::orderBy('ordre')->get();
        return view('admin.classes.create', compact('filieres', 'niveaux'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'filiere_id' => 'required|exists:filieres,id',
            'niveau_id' => 'required|exists:niveaux,id',
        ]);

        Classe::create($validated);
        return redirect()->route('admin.classes.index')->with('success', 'Classe créée avec succès.');
    }

    public function edit(Classe $classe)
    {
        $filieres = Filiere::all();
        $niveaux = Niveau::orderBy('ordre')->get();
        return view('admin.classes.edit', compact('classe', 'filieres', 'niveaux'));
    }

    public function update(Request $request, Classe $classe)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'filiere_id' => 'required|exists:filieres,id',
            'niveau_id' => 'required|exists:niveaux,id',
        ]);

        $classe->update($validated);
        return redirect()->route('admin.classes.index')->with('success', 'Classe mise à jour avec succès.');
    }

    public function destroy(Classe $classe)
    {
        $classe->delete();
        return redirect()->route('admin.classes.index')->with('success', 'Classe supprimée avec succès.');
    }
}
