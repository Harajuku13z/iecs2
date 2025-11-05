<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StaticPageController extends Controller
{
    public function index()
    {
        $pages = StaticPage::orderBy('menu_ordre')->orderBy('titre')->paginate(20);
        return view('admin.static-pages.index', compact('pages'));
    }

    public function create()
    {
        $menuParents = StaticPage::getMenuParents();
        return view('admin.static-pages.create', compact('menuParents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:static_pages,slug',
            'description' => 'nullable|string',
            'contenu' => 'required|string',
            'type_contenu' => 'required|in:html,texte',
            'image_principale' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'menu_nom' => 'nullable|string|max:255',
            'menu_parent' => 'nullable|string|max:255',
            'menu_ordre' => 'nullable|integer|min:0',
            'publie' => 'boolean',
            'afficher_menu' => 'boolean',
        ]);

        // Générer le slug si non fourni
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['titre']);
        }

        // Gérer l'upload de l'image
        if ($request->hasFile('image_principale')) {
            $validated['image_principale'] = $request->file('image_principale')->store('static-pages', 'public');
        }

        // Convertir les booléens
        $validated['publie'] = $request->has('publie');
        $validated['afficher_menu'] = $request->has('afficher_menu');

        StaticPage::create($validated);

        return redirect()->route('admin.static-pages.index')
            ->with('success', 'Page statique créée avec succès.');
    }

    public function edit(StaticPage $staticPage)
    {
        $menuParents = StaticPage::getMenuParents();
        return view('admin.static-pages.edit', compact('staticPage', 'menuParents'));
    }

    public function update(Request $request, StaticPage $staticPage)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:static_pages,slug,' . $staticPage->id,
            'description' => 'nullable|string',
            'contenu' => 'required|string',
            'type_contenu' => 'required|in:html,texte',
            'image_principale' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'menu_nom' => 'nullable|string|max:255',
            'menu_parent' => 'nullable|string|max:255',
            'menu_ordre' => 'nullable|integer|min:0',
            'publie' => 'boolean',
            'afficher_menu' => 'boolean',
        ]);

        // Générer le slug si non fourni
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['titre']);
        }

        // Gérer l'upload de l'image
        if ($request->hasFile('image_principale')) {
            // Supprimer l'ancienne image
            if ($staticPage->image_principale) {
                Storage::disk('public')->delete($staticPage->image_principale);
            }
            $validated['image_principale'] = $request->file('image_principale')->store('static-pages', 'public');
        }

        // Convertir les booléens
        $validated['publie'] = $request->has('publie');
        $validated['afficher_menu'] = $request->has('afficher_menu');

        $staticPage->update($validated);

        return redirect()->route('admin.static-pages.index')
            ->with('success', 'Page statique mise à jour avec succès.');
    }

    public function destroy(StaticPage $staticPage)
    {
        // Supprimer l'image
        if ($staticPage->image_principale) {
            Storage::disk('public')->delete($staticPage->image_principale);
        }

        $staticPage->delete();

        return redirect()->route('admin.static-pages.index')
            ->with('success', 'Page statique supprimée avec succès.');
    }
}

