<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StaticPageController extends Controller
{
    /**
     * Afficher la liste des pages statiques
     */
    public function index()
    {
        $pages = StaticPage::orderBy('menu_ordre')->orderBy('titre')->paginate(20);
        return view('admin.static-pages.index', compact('pages'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        // Récupérer les menus existants pour les sous-menus
        $mainMenuItems = StaticPage::mainMenu()->menuVisible()->get();
        $existingPages = StaticPage::all();
        
        return view('admin.static-pages.create', compact('mainMenuItems', 'existingPages'));
    }

    /**
     * Enregistrer une nouvelle page
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'contenu' => 'required|string',
            'type_contenu' => 'required|in:html,texte',
            'image_principale' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'menu_nom' => 'nullable|string|max:255',
            'menu_parent' => 'nullable|string|max:255',
            'menu_ordre' => 'nullable|integer|min:0',
            'publie' => 'boolean',
            'afficher_menu' => 'boolean',
        ]);

        // Générer le slug
        $slug = StaticPage::generateSlug($request->titre);

        // Gérer l'upload de l'image
        $imagePath = null;
        if ($request->hasFile('image_principale')) {
            $file = $request->file('image_principale');
            $filename = 'page-' . time() . '-' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $imagePath = $file->storeAs('', $filename, 'public');
        }

        $page = StaticPage::create([
            'titre' => $request->titre,
            'slug' => $slug,
            'description' => $request->description,
            'contenu' => $request->contenu,
            'type_contenu' => $request->type_contenu,
            'image_principale' => $imagePath,
            'menu_nom' => $request->menu_nom ?: $request->titre,
            'menu_parent' => $request->menu_parent ?: null,
            'menu_ordre' => $request->menu_ordre ?: 0,
            'publie' => $request->boolean('publie'),
            'afficher_menu' => $request->boolean('afficher_menu'),
        ]);

        return redirect()->route('admin.static-pages.index')
            ->with('success', 'Page statique créée avec succès !');
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(StaticPage $staticPage)
    {
        $mainMenuItems = StaticPage::mainMenu()->menuVisible()->get();
        $existingPages = StaticPage::where('id', '!=', $staticPage->id)->get();
        
        return view('admin.static-pages.edit', compact('staticPage', 'mainMenuItems', 'existingPages'));
    }

    /**
     * Mettre à jour une page
     */
    public function update(Request $request, StaticPage $staticPage)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'contenu' => 'required|string',
            'type_contenu' => 'required|in:html,texte',
            'image_principale' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'menu_nom' => 'nullable|string|max:255',
            'menu_parent' => 'nullable|string|max:255',
            'menu_ordre' => 'nullable|integer|min:0',
            'publie' => 'boolean',
            'afficher_menu' => 'boolean',
        ]);

        // Si le titre change, régénérer le slug
        if ($request->titre !== $staticPage->titre) {
            $slug = StaticPage::generateSlug($request->titre);
        } else {
            $slug = $staticPage->slug;
        }

        // Gérer l'upload de la nouvelle image
        $imagePath = $staticPage->image_principale;
        if ($request->hasFile('image_principale')) {
            // Supprimer l'ancienne image
            if ($staticPage->image_principale && Storage::disk('public')->exists($staticPage->image_principale)) {
                Storage::disk('public')->delete($staticPage->image_principale);
            }
            
            $file = $request->file('image_principale');
            $filename = 'page-' . time() . '-' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $imagePath = $file->storeAs('', $filename, 'public');
        }

        $staticPage->update([
            'titre' => $request->titre,
            'slug' => $slug,
            'description' => $request->description,
            'contenu' => $request->contenu,
            'type_contenu' => $request->type_contenu,
            'image_principale' => $imagePath,
            'menu_nom' => $request->menu_nom ?: $request->titre,
            'menu_parent' => $request->menu_parent ?: null,
            'menu_ordre' => $request->menu_ordre ?: 0,
            'publie' => $request->boolean('publie'),
            'afficher_menu' => $request->boolean('afficher_menu'),
        ]);

        return redirect()->route('admin.static-pages.index')
            ->with('success', 'Page statique mise à jour avec succès !');
    }

    /**
     * Supprimer une page
     */
    public function destroy(StaticPage $staticPage)
    {
        // Supprimer l'image associée
        if ($staticPage->image_principale && Storage::disk('public')->exists($staticPage->image_principale)) {
            Storage::disk('public')->delete($staticPage->image_principale);
        }

        $staticPage->delete();

        return redirect()->route('admin.static-pages.index')
            ->with('success', 'Page statique supprimée avec succès !');
    }
}

