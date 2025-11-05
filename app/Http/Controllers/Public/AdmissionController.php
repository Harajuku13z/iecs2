<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class AdmissionController extends Controller
{
    public function index()
    {
        return view('public.admission');
    }

    public function downloadPdf()
    {
        // Si un PDF est uploadé, le retourner directement
        $pdfFile = Setting::get('admission_pdf_file', '');
        if ($pdfFile) {
            $filePath = storage_path('app/public/' . $pdfFile);
            if (file_exists($filePath)) {
                return response()->download($filePath, 'dossier-information-iesca.pdf', [
                    'Content-Type' => 'application/pdf',
                ]);
            }
        }
        
        // Sinon générer le PDF avec les mêmes données que l'admin
        $settings = [
            'title' => Setting::get('admission_title', 'Procédure d\'Admission'),
            'subtitle' => Setting::get('admission_subtitle', 'Rejoignez l\'excellence académique - Année 2025-2026'),
            'inscription_start_date' => Setting::get('inscription_start_date', now()->format('Y-m-d')),
            'debut_cours' => Setting::get('debut_cours', now()->format('Y-m-d')),
            'frais_inscription' => Setting::get('admission_frais_inscription', Setting::get('frais_inscription', 30000)),
            'frais_mensuels' => Setting::get('admission_frais_mensuels', Setting::get('frais_mensuels', 35000)),
            'frais_bonus' => Setting::get('admission_frais_bonus', "Carte d'étudiant : Gratuite\nTote : Gratuite\nAssurance : Gratuite"),
            'documents' => Setting::get('admission_documents', ''),
            'services' => Setting::get('admission_services', ''),
            'conditions_l1' => Setting::get('admission_conditions_l1', ''),
            'conditions_l2' => Setting::get('admission_conditions_l2', ''),
            'conditions_l3' => Setting::get('admission_conditions_l3', ''),
            'conditions_m1' => Setting::get('admission_conditions_m1', ''),
            'conditions_fc' => Setting::get('admission_conditions_fc', ''),
            'conditions_m2' => Setting::get('admission_conditions_m2', ''),
            'dossier_l1' => Setting::get('admission_dossier_l1', ''),
            'dossier_m1' => Setting::get('admission_dossier_m1', ''),
            'avantage' => Setting::get('admission_avantage', ''),
            'contact_title' => Setting::get('admission_contact_title', 'Besoin d\'Aide ?'),
            'contact_text' => Setting::get('admission_contact_text', 'Contactez-nous :'),
            'phone1' => Setting::get('phone1', ''),
            'phone2' => Setting::get('phone2', ''),
            'email' => Setting::get('email', ''),
        ];

        $html = view('public.admission-pdf', $settings)->render();
        
        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download('dossier-information-iesca.pdf');
    }
}

