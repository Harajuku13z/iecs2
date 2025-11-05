<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActualiteContentController extends Controller
{
    public function index()
    {
        return view('admin.actualite-content.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'actualites_hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);
        
        // Textes Hero
        Setting::updateOrCreate(['cle' => 'actualites_hero_title'], ['valeur' => $request->actualites_hero_title, 'description' => 'Titre principal du hero actualités']);
        Setting::updateOrCreate(['cle' => 'actualites_hero_subtitle'], ['valeur' => $request->actualites_hero_subtitle, 'description' => 'Sous-titre du hero actualités']);
        
        // Image Hero
        if ($request->hasFile('actualites_hero_image')) {
            $file = $request->file('actualites_hero_image');
            
            // Supprimer l'ancienne image si elle existe
            $oldImage = Setting::where('cle', 'actualites_hero_image')->first();
            if ($oldImage && $oldImage->valeur && Storage::disk('public')->exists($oldImage->valeur)) {
                Storage::disk('public')->delete($oldImage->valeur);
            }
            
            $filename = 'actualites-hero-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('', $filename, 'public');
            
            Setting::updateOrCreate(
                ['cle' => 'actualites_hero_image'],
                ['valeur' => $filename, 'description' => 'Image de fond du hero actualités']
            );
        }
        
        return redirect()->route('admin.actualite-content.index')->with('success', 'Configuration de la page actualités mise à jour avec succès !');
    }
}