<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use Illuminate\Http\Request;

class CoursController extends Controller
{
    public function index()
    {
        $cours = Cours::orderBy('nom')->paginate(15);
        return view('admin.cours.index', compact('cours'));
    }

    public function create()
    {
        return view('admin.cours.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:cours',
            'coefficient' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        Cours::create($validated);
        return redirect()->route('admin.cours.index')->with('success', 'Cours créé avec succès.');
    }

    public function edit(Cours $cour)
    {
        return view('admin.cours.edit', compact('cour'));
    }

    public function update(Request $request, Cours $cour)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:cours,code,' . $cour->id,
            'coefficient' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $cour->update($validated);
        return redirect()->route('admin.cours.index')->with('success', 'Cours mis à jour avec succès.');
    }

    public function destroy(Cours $cour)
    {
        $cour->delete();
        return redirect()->route('admin.cours.index')->with('success', 'Cours supprimé avec succès.');
    }
}
