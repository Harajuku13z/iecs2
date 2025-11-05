<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class AdmissionContentController extends Controller
{
    public function index()
    {
        return view('admin.admission-content.index');
    }

    public function update(Request $request)
    {
        // Titre et sous-titre de la page admission
        Setting::updateOrCreate(['cle' => 'admission_title'], ['valeur' => $request->admission_title, 'description' => 'Titre de la page admission']);
        Setting::updateOrCreate(['cle' => 'admission_subtitle'], ['valeur' => $request->admission_subtitle, 'description' => 'Sous-titre de la page admission']);
        
        // Documents requis (séparés par des retours à la ligne)
        Setting::updateOrCreate(['cle' => 'admission_documents'], ['valeur' => $request->admission_documents, 'description' => 'Liste des documents requis (séparés par retour à la ligne)']);
        
        // Services disponibles (séparés par des retours à la ligne)
        Setting::updateOrCreate(['cle' => 'admission_services'], ['valeur' => $request->admission_services, 'description' => 'Liste des services disponibles (séparés par retour à la ligne)']);
        
        // Conditions Premier Cycle
        Setting::updateOrCreate(['cle' => 'admission_conditions_l1'], ['valeur' => $request->admission_conditions_l1, 'description' => 'Conditions L1']);
        Setting::updateOrCreate(['cle' => 'admission_conditions_l2'], ['valeur' => $request->admission_conditions_l2, 'description' => 'Conditions L2']);
        Setting::updateOrCreate(['cle' => 'admission_conditions_l3'], ['valeur' => $request->admission_conditions_l3, 'description' => 'Conditions L3']);
        
        // Conditions Deuxième Cycle
        Setting::updateOrCreate(['cle' => 'admission_conditions_m1'], ['valeur' => $request->admission_conditions_m1, 'description' => 'Conditions Master 1']);
        Setting::updateOrCreate(['cle' => 'admission_conditions_fc'], ['valeur' => $request->admission_conditions_fc, 'description' => 'Conditions Formation Continue']);
        Setting::updateOrCreate(['cle' => 'admission_conditions_m2'], ['valeur' => $request->admission_conditions_m2, 'description' => 'Conditions Master 2']);
        
        // Dossier à fournir
        Setting::updateOrCreate(['cle' => 'admission_dossier_l1'], ['valeur' => $request->admission_dossier_l1, 'description' => 'Dossier Premier Cycle']);
        Setting::updateOrCreate(['cle' => 'admission_dossier_m1'], ['valeur' => $request->admission_dossier_m1, 'description' => 'Dossier Deuxième Cycle']);
        
        // Avantage spécial
        Setting::updateOrCreate(['cle' => 'admission_avantage'], ['valeur' => $request->admission_avantage, 'description' => 'Avantage spécial']);

        // Dates importantes
        if ($request->filled('inscription_start_date')) {
            Setting::updateOrCreate(['cle' => 'inscription_start_date'], ['valeur' => $request->inscription_start_date, 'description' => 'Début des inscriptions']);
        }
        if ($request->filled('debut_cours')) {
            Setting::updateOrCreate(['cle' => 'debut_cours'], ['valeur' => $request->debut_cours, 'description' => 'Début des cours']);
        }
        
        // Frais
        Setting::updateOrCreate(['cle' => 'admission_frais_inscription'], ['valeur' => $request->admission_frais_inscription, 'description' => 'Frais d\'inscription']);
        Setting::updateOrCreate(['cle' => 'admission_frais_mensuels'], ['valeur' => $request->admission_frais_mensuels, 'description' => 'Frais mensuels']);
        Setting::updateOrCreate(['cle' => 'admission_frais_bonus'], ['valeur' => $request->admission_frais_bonus, 'description' => 'Bonus inclus dans les frais']);
        
        // Contact
        Setting::updateOrCreate(['cle' => 'admission_contact_title'], ['valeur' => $request->admission_contact_title, 'description' => 'Titre section contact']);
        Setting::updateOrCreate(['cle' => 'admission_contact_text'], ['valeur' => $request->admission_contact_text, 'description' => 'Texte section contact']);
        
        // PDF du dossier
        if ($request->hasFile('admission_pdf_file')) {
            $file = $request->file('admission_pdf_file');
            
            // Valider que c'est bien un PDF
            if ($file->getClientOriginalExtension() !== 'pdf') {
                return redirect()->route('admin.admission-content.index')
                    ->with('error', 'Le fichier doit être un PDF.');
            }
            
            $filename = 'admission-dossier-' . time() . '.pdf';
            $path = Storage::disk('public')->putFileAs('', $file, $filename);
            
            // Sauvegarder le chemin du fichier
            Setting::updateOrCreate(
                ['cle' => 'admission_pdf_file'],
                ['valeur' => $filename, 'description' => 'PDF du dossier d\'information']
            );
        }
        
        // Options d'affichage des sections
        Setting::updateOrCreate(['cle' => 'admission_enable_header'], ['valeur' => $request->has('enable_header') ? '1' : '0', 'description' => 'Afficher l\'en-tête']);
        Setting::updateOrCreate(['cle' => 'admission_enable_documents'], ['valeur' => $request->has('enable_documents') ? '1' : '0', 'description' => 'Afficher documents requis']);
        Setting::updateOrCreate(['cle' => 'admission_enable_services'], ['valeur' => $request->has('enable_services') ? '1' : '0', 'description' => 'Afficher services disponibles']);
        Setting::updateOrCreate(['cle' => 'admission_enable_conditions_l1'], ['valeur' => $request->has('enable_conditions_l1') ? '1' : '0', 'description' => 'Afficher conditions Premier Cycle']);
        Setting::updateOrCreate(['cle' => 'admission_enable_conditions_m1'], ['valeur' => $request->has('enable_conditions_m1') ? '1' : '0', 'description' => 'Afficher conditions Deuxième Cycle']);
        Setting::updateOrCreate(['cle' => 'admission_enable_dossier'], ['valeur' => $request->has('enable_dossier') ? '1' : '0', 'description' => 'Afficher dossier à fournir']);
        Setting::updateOrCreate(['cle' => 'admission_enable_avantage'], ['valeur' => $request->has('enable_avantage') ? '1' : '0', 'description' => 'Afficher avantage spécial']);
        Setting::updateOrCreate(['cle' => 'admission_enable_frais'], ['valeur' => $request->has('enable_frais') ? '1' : '0', 'description' => 'Afficher frais']);
        Setting::updateOrCreate(['cle' => 'admission_enable_contact'], ['valeur' => $request->has('enable_contact') ? '1' : '0', 'description' => 'Afficher contact']);
        Setting::updateOrCreate(['cle' => 'admission_enable_pdf'], ['valeur' => $request->has('enable_pdf') ? '1' : '0', 'description' => 'Afficher bouton PDF']);
        
        return redirect()->route('admin.admission-content.index')
            ->with('success', 'Contenus de la page admission mis à jour avec succès.');
    }
}

