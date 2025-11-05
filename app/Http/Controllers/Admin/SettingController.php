<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        // Créer les settings de réseaux sociaux s'ils n'existent pas
        $socialNetworks = [
            'social_facebook' => 'Lien Facebook',
            'social_twitter' => 'Lien Twitter/X',
            'social_instagram' => 'Lien Instagram',
            'social_linkedin' => 'Lien LinkedIn',
            'social_youtube' => 'Lien YouTube',
        ];
        
        foreach ($socialNetworks as $key => $description) {
            Setting::firstOrCreate(
                ['cle' => $key],
                ['valeur' => '', 'description' => $description]
            );
        }
        
        $settings = Setting::all();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        // Liste des clés qui appartiennent à la page d'accueil ou autres pages (à exclure)
        $homepageKeys = [
            'hero_title', 'hero_subtitle', 'hero_image',
            'about_title', 'about_text1', 'about_text2', 'about_image',
            'about_feature_1_icon', 'about_feature_1_title', 'about_feature_1_description',
            'about_feature_2_icon', 'about_feature_2_title', 'about_feature_2_description',
            'about_feature_3_icon', 'about_feature_3_title', 'about_feature_3_description',
            'about_feature_4_icon', 'about_feature_4_title', 'about_feature_4_description',
            'about_feature_5_icon', 'about_feature_5_title', 'about_feature_5_description',
            'about_feature_6_icon', 'about_feature_6_title', 'about_feature_6_description',
            'about_feature_7_icon', 'about_feature_7_title', 'about_feature_7_description',
            'filieres_title',
            'admission_process_title', 'admission_process_intro', 'admission_process_image',
            'admission_step_1_title', 'admission_step_1_description',
            'admission_step_2_title', 'admission_step_2_description',
            'admission_step_3_title', 'admission_step_3_description',
            'admission_step_4_title', 'admission_step_4_description',
            'cta_title', 'cta_subtitle', 'cta_background_image',
            'homepage_title', 'banner_image', 'inscription_start_date', 'frais_mensuels',
            'events_hero_title', 'events_hero_subtitle', 'events_hero_image',
        ];
        
        foreach ($request->except(array_merge(['_token', 'logo'], $homepageKeys)) as $cle => $valeur) {
            Setting::updateOrCreate(
                ['cle' => $cle],
                ['valeur' => $valeur, 'description' => $this->getSettingDescription($cle)]
            );
        }

        // Gestion de l'upload du logo
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'logo-' . time() . '.' . $file->getClientOriginalExtension();
            $path = Storage::disk('public')->putFileAs('', $file, $filename);
            Setting::updateOrCreate(
                ['cle' => 'logo'],
                ['valeur' => $filename, 'description' => 'Logo de l\'IESCA']
            );
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Paramètres mis à jour avec succès.');
    }

    public function sendTestEmail(Request $request)
    {
        $data = $request->validate([
            'test_email' => 'required|email',
        ]);

        try {
            \Illuminate\Support\Facades\Mail::raw('Test email IESCA - configuration SMTP OK.', function ($message) use ($data) {
                $message->to($data['test_email'])->subject('Test Email IESCA');
            });
        } catch (\Throwable $e) {
            return back()->with('error', 'Échec de l\'envoi: ' . $e->getMessage());
        }

        return back()->with('success', 'Email de test envoyé à ' . $data['test_email']);
    }

    private function getSettingDescription($cle)
    {
        $descriptions = [
            'social_facebook' => 'Lien Facebook',
            'social_twitter' => 'Lien Twitter/X',
            'social_instagram' => 'Lien Instagram',
            'social_linkedin' => 'Lien LinkedIn',
            'social_youtube' => 'Lien YouTube',
        ];
        
        return $descriptions[$cle] ?? ucfirst(str_replace('_', ' ', $cle));
    }
}
