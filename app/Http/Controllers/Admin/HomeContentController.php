<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeContentController extends Controller
{
    public function index()
    {
        return view('admin.home-content.index');
    }

    public function update(Request $request)
    {
        // Textes Hero
        Setting::updateOrCreate(['cle' => 'hero_title'], ['valeur' => $request->hero_title, 'description' => 'Titre principal du hero']);
        Setting::updateOrCreate(['cle' => 'hero_subtitle'], ['valeur' => $request->hero_subtitle, 'description' => 'Sous-titre du hero']);
        
        // Image Hero
        if ($request->hasFile('hero_image')) {
            $file = $request->file('hero_image');
            $filename = 'hero-' . time() . '.' . $file->getClientOriginalExtension();
            $path = Storage::disk('public')->putFileAs('', $file, $filename);
            Setting::updateOrCreate(
                ['cle' => 'hero_image'],
                ['valeur' => $filename, 'description' => 'Image de fond du hero']
            );
        }
        
        // Textes À Propos
        Setting::updateOrCreate(['cle' => 'about_title'], ['valeur' => $request->about_title, 'description' => 'Titre section À Propos']);
        Setting::updateOrCreate(['cle' => 'about_text1'], ['valeur' => $request->about_text1, 'description' => 'Premier paragraphe À Propos']);
        Setting::updateOrCreate(['cle' => 'about_text2'], ['valeur' => $request->about_text2, 'description' => 'Deuxième paragraphe À Propos']);
        
        // Image À Propos
        if ($request->hasFile('about_image')) {
            $file = $request->file('about_image');
            $filename = 'about-' . time() . '.' . $file->getClientOriginalExtension();
            $path = Storage::disk('public')->putFileAs('', $file, $filename);
            Setting::updateOrCreate(
                ['cle' => 'about_image'],
                ['valeur' => $filename, 'description' => 'Image section À Propos']
            );
        }
        
        // Atouts (6 seulement)
        $atouts = [
            ['icon' => '💻', 'title' => 'Salle d\'Informatique', 'description' => 'Équipements modernes et performants'],
            ['icon' => '📚', 'title' => 'Bibliothèque', 'description' => 'Ressources académiques complètes'],
            ['icon' => '❄️', 'title' => 'Classes Climatisées', 'description' => 'Confort optimal pour l\'apprentissage'],
            ['icon' => '👨‍🏫', 'title' => 'Formation Complète', 'description' => 'Cours théoriques et pratiques'],
            ['icon' => '📹', 'title' => 'Caméras de Surveillance', 'description' => 'Sécurité assurée 24/7'],
            ['icon' => '🏢', 'title' => 'Stage Garanti', 'description' => 'En fin de formation'],
        ];
        
        foreach ($atouts as $index => $atout) {
            $iconKey = 'about_feature_' . ($index + 1) . '_icon';
            $titleKey = 'about_feature_' . ($index + 1) . '_title';
            $descKey = 'about_feature_' . ($index + 1) . '_description';
            
            Setting::updateOrCreate(['cle' => $iconKey], ['valeur' => $request->input($iconKey, $atout['icon']), 'description' => 'Icône atout ' . ($index + 1)]);
            Setting::updateOrCreate(['cle' => $titleKey], ['valeur' => $request->input($titleKey, $atout['title']), 'description' => 'Titre atout ' . ($index + 1)]);
            Setting::updateOrCreate(['cle' => $descKey], ['valeur' => $request->input($descKey, $atout['description']), 'description' => 'Description atout ' . ($index + 1)]);
        }
        
        // Titre section Filières
        Setting::updateOrCreate(['cle' => 'filieres_title'], ['valeur' => $request->filieres_title, 'description' => 'Titre section Filières']);
        
        // Processus d'Admission
        Setting::updateOrCreate(['cle' => 'admission_process_title'], ['valeur' => $request->admission_process_title, 'description' => 'Titre section Processus d\'Admission']);
        Setting::updateOrCreate(['cle' => 'admission_process_intro'], ['valeur' => $request->admission_process_intro, 'description' => 'Introduction section Processus d\'Admission']);
        
        // Section CTA
        Setting::updateOrCreate(['cle' => 'cta_title'], ['valeur' => $request->cta_title, 'description' => 'Titre section CTA']);
        Setting::updateOrCreate(['cle' => 'cta_subtitle'], ['valeur' => $request->cta_subtitle, 'description' => 'Sous-titre section CTA']);
        
        // Image CTA
        if ($request->hasFile('cta_background_image')) {
            $file = $request->file('cta_background_image');
            $filename = 'cta-bg-' . time() . '.' . $file->getClientOriginalExtension();
            $path = Storage::disk('public')->putFileAs('', $file, $filename);
            Setting::updateOrCreate(
                ['cle' => 'cta_background_image'],
                ['valeur' => $filename, 'description' => 'Image de fond section CTA']
            );
        }
        
        return redirect()->route('admin.home-content.index')
            ->with('success', 'Contenus de la page d\'accueil mis à jour avec succès.');
    }
}
