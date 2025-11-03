<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Niveau;
use Illuminate\Http\Request;

class NiveauController extends Controller
{
    public function index()
    {
        $niveaux = Niveau::orderBy('ordre')->paginate(15);
        return view('admin.niveaux.index', compact('niveaux'));
    }

    public function create()
    {
        return view('admin.niveaux.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'ordre' => 'required|integer|min:1',
        ]);

        Niveau::create($validated);
        return redirect()->route('admin.niveaux.index')->with('success', 'Niveau créé avec succès.');
    }

    public function edit(Niveau $niveau)
    {
        return view('admin.niveaux.edit', compact('niveau'));
    }

    public function update(Request $request, Niveau $niveau)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'ordre' => 'required|integer|min:1',
        ]);

        $niveau->update($validated);
        return redirect()->route('admin.niveaux.index')->with('success', 'Niveau mis à jour avec succès.');
    }

    public function destroy(Niveau $niveau)
    {
        $niveau->delete();
        return redirect()->route('admin.niveaux.index')->with('success', 'Niveau supprimé avec succès.');
    }
}
