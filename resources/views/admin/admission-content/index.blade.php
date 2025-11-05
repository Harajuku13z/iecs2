@extends('layouts.admin')

@section('title', 'Contenus Page Admission')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>📝 Contenus de la Page Admission</h1>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form action="{{ route('admin.admission-content.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <!-- Titre et Sous-titre -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">🎯 En-tête de la Page</h5>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="enable_header" name="enable_header" value="1" 
                       {{ \App\Models\Setting::get('admission_enable_header', '1') == '1' ? 'checked' : '' }}>
                <label class="form-check-label text-white" for="enable_header">Afficher</label>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="admission_title" class="form-label">Titre Principal</label>
                <input type="text" class="form-control" id="admission_title" name="admission_title" 
                       value="{{ \App\Models\Setting::get('admission_title', 'Procédure d\'Admission') }}">
            </div>
            <div class="mb-3">
                <label for="admission_subtitle" class="form-label">Sous-titre</label>
                <input type="text" class="form-control" id="admission_subtitle" name="admission_subtitle" 
                       value="{{ \App\Models\Setting::get('admission_subtitle', 'Rejoignez l\'excellence académique - Année 2025-2026') }}">
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="inscription_start_date" class="form-label">Début des Inscriptions</label>
                    <input type="date" class="form-control" id="inscription_start_date" name="inscription_start_date"
                           value="{{ \App\Models\Setting::get('inscription_start_date', now()->format('Y-m-d')) }}">
                </div>
                <div class="col-md-6">
                    <label for="debut_cours" class="form-label">Début des Cours</label>
                    <input type="date" class="form-control" id="debut_cours" name="debut_cours"
                           value="{{ \App\Models\Setting::get('debut_cours', now()->format('Y-m-d')) }}">
                </div>
            </div>
        </div>
    </div>

    <!-- Documents Requis -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">📋 Documents Requis</h5>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="enable_documents" name="enable_documents" value="1" 
                       {{ \App\Models\Setting::get('admission_enable_documents', '1') == '1' ? 'checked' : '' }}>
                <label class="form-check-label text-white" for="enable_documents">Afficher</label>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="admission_documents" class="form-label">Liste des documents (un par ligne)</label>
                <textarea class="form-control" id="admission_documents" name="admission_documents" rows="5">{{ \App\Models\Setting::get('admission_documents', "Photocopie du Diplôme (BAC)\nPhotocopie en couleur de l'acte de naissance\nUne enveloppe kraft A4\n1 Paquet de marqueur tableau blanc (Permanent)") }}</textarea>
                <small class="text-muted">Chaque ligne sera un élément de la liste</small>
            </div>
        </div>
    </div>

    <!-- Services Disponibles -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">📋 Services Disponibles</h5>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="enable_services" name="enable_services" value="1" 
                       {{ \App\Models\Setting::get('admission_enable_services', '1') == '1' ? 'checked' : '' }}>
                <label class="form-check-label text-white" for="enable_services">Afficher</label>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="admission_services" class="form-label">Liste des services (un par ligne)</label>
                <textarea class="form-control" id="admission_services" name="admission_services" rows="10">{{ \App\Models\Setting::get('admission_services', "Inscription / Réinscription\nCoût de la Formation\nCalendrier académique 2024-2025 et Horaires de cours\nPublication des résultats des examens\nGuide de l'étudiant 2024-2025\nCarte de l'étudiant 2024-2025\nCursus de l'étudiant\nAttestation de fréquentation\nAttestation d'inscription\nAssiduité de l'étudiant\nRelevés des notes\nAssistance des étudiants\nDélivrance de diplôme") }}</textarea>
                <small class="text-muted">Chaque ligne sera un service affiché avec ✅</small>
            </div>
        </div>
    </div>

    <!-- Conditions d'inscription Premier Cycle -->
    <div class="card mb-4">
        <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
            <h5 class="mb-0">📚 Conditions d'inscription - Premier Cycle</h5>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="enable_conditions_l1" name="enable_conditions_l1" value="1" 
                       {{ \App\Models\Setting::get('admission_enable_conditions_l1', '1') == '1' ? 'checked' : '' }}>
                <label class="form-check-label" for="enable_conditions_l1">Afficher</label>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="admission_conditions_l1" class="form-label">1ère année (une par ligne)</label>
                <textarea class="form-control" id="admission_conditions_l1" name="admission_conditions_l1" rows="2">{{ \App\Models\Setting::get('admission_conditions_l1', "Être titulaire d'un Baccalauréat ou d'un diplôme équivalent.") }}</textarea>
            </div>
            <div class="mb-3">
                <label for="admission_conditions_l2" class="form-label">2ème année (une par ligne)</label>
                <textarea class="form-control" id="admission_conditions_l2" name="admission_conditions_l2" rows="2">{{ \App\Models\Setting::get('admission_conditions_l2', "Fournir un dossier scolaire de l'ESGAE\nOu avoir une équivalence de 60 CECT (Crédits d'évaluation capitalisables et transférables).") }}</textarea>
            </div>
            <div class="mb-3">
                <label for="admission_conditions_l3" class="form-label">3ème année (une par ligne)</label>
                <textarea class="form-control" id="admission_conditions_l3" name="admission_conditions_l3" rows="2">{{ \App\Models\Setting::get('admission_conditions_l3', "Être titulaire d'un BTSE, BTS ou d'un diplôme équivalent avec 120 CECT.") }}</textarea>
            </div>
        </div>
    </div>

    <!-- Conditions d'inscription Deuxième Cycle -->
    <div class="card mb-4">
        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">📚 Conditions d'inscription - Deuxième Cycle</h5>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="enable_conditions_m1" name="enable_conditions_m1" value="1" 
                       {{ \App\Models\Setting::get('admission_enable_conditions_m1', '1') == '1' ? 'checked' : '' }}>
                <label class="form-check-label text-white" for="enable_conditions_m1">Afficher</label>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="admission_conditions_m1" class="form-label">Master 1 (une par ligne)</label>
                <textarea class="form-control" id="admission_conditions_m1" name="admission_conditions_m1" rows="2">{{ \App\Models\Setting::get('admission_conditions_m1', "Les titulaires d'une Licence (Bac+3) en gestion, économie, droit, médecine… ou d'une équivalence ECTS de 180 CECT.") }}</textarea>
            </div>
            <div class="mb-3">
                <label for="admission_conditions_fc" class="form-label">Formation continue (une par ligne)</label>
                <textarea class="form-control" id="admission_conditions_fc" name="admission_conditions_fc" rows="2">{{ \App\Models\Setting::get('admission_conditions_fc', "Être titulaire d'une Licence, d'un diplôme équivalent ou d'une équivalence ECTS de 180 CECT\nLa durée Formation continue est de 1 année 📅") }}</textarea>
            </div>
            <div class="mb-3">
                <label for="admission_conditions_m2" class="form-label">Master 2 (une par ligne)</label>
                <textarea class="form-control" id="admission_conditions_m2" name="admission_conditions_m2" rows="2">{{ \App\Models\Setting::get('admission_conditions_m2', "Les titulaires du diplôme d'ingénieur de l'ESGAE, du Master 1 de l'ESGAE ou du Certificat d'Etudes Supérieures en Administration des Entreprises CESAE de l'ESGAE") }}</textarea>
            </div>
        </div>
    </div>

    <!-- Dossier à fournir -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">📄 Dossier à fournir</h5>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="enable_dossier" name="enable_dossier" value="1" 
                       {{ \App\Models\Setting::get('admission_enable_dossier', '1') == '1' ? 'checked' : '' }}>
                <label class="form-check-label text-white" for="enable_dossier">Afficher</label>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="admission_dossier_l1" class="form-label">Premier cycle (une par ligne)</label>
                <textarea class="form-control" id="admission_dossier_l1" name="admission_dossier_l1" rows="4">{{ \App\Models\Setting::get('admission_dossier_l1', "1 extrait d'acte de naissance (original ou copie certifiée conforme)\n2 photos d'identité couleur sur fond blanc\n1 copie légalisée du baccalauréat") }}</textarea>
            </div>
            <div class="mb-3">
                <label for="admission_dossier_m1" class="form-label">Deuxième cycle (une par ligne)</label>
                <textarea class="form-control" id="admission_dossier_m1" name="admission_dossier_m1" rows="2">{{ \App\Models\Setting::get('admission_dossier_m1', "1 copie légalisée de la licence ou d'un diplôme équivalent") }}</textarea>
            </div>
        </div>
    </div>

    <!-- Avantage spécial -->
    <div class="card mb-4">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">💻 Avantage Spécial</h5>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="enable_avantage" name="enable_avantage" value="1" 
                       {{ \App\Models\Setting::get('admission_enable_avantage', '1') == '1' ? 'checked' : '' }}>
                <label class="form-check-label text-white" for="enable_avantage">Afficher</label>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="admission_avantage" class="form-label">Texte de l'avantage spécial</label>
                <textarea class="form-control" id="admission_avantage" name="admission_avantage" rows="2">{{ \App\Models\Setting::get('admission_avantage', "Possibilité d'achat d'un ordinateur à crédit pour faciliter vos études.") }}</textarea>
            </div>
        </div>
    </div>

    <!-- Frais d'inscription et Mensuels -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">💰 Frais</h5>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="enable_frais" name="enable_frais" value="1" 
                       {{ \App\Models\Setting::get('admission_enable_frais', '1') == '1' ? 'checked' : '' }}>
                <label class="form-check-label text-white" for="enable_frais">Afficher</label>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="admission_frais_inscription" class="form-label">Frais d'inscription (FCFA)</label>
                    <input type="number" class="form-control" id="admission_frais_inscription" name="admission_frais_inscription" 
                           value="{{ \App\Models\Setting::get('admission_frais_inscription', \App\Models\Setting::get('frais_inscription', 30000)) }}">
                </div>
                <div class="col-md-6">
                    <label for="admission_frais_mensuels" class="form-label">Frais mensuels (FCFA)</label>
                    <input type="number" class="form-control" id="admission_frais_mensuels" name="admission_frais_mensuels" 
                           value="{{ \App\Models\Setting::get('admission_frais_mensuels', \App\Models\Setting::get('frais_mensuels', 35000)) }}">
                </div>
                <div class="col-md-12">
                    <label for="admission_frais_bonus" class="form-label">Bonus/Inclus (une par ligne)</label>
                    <textarea class="form-control" id="admission_frais_bonus" name="admission_frais_bonus" rows="3">{{ \App\Models\Setting::get('admission_frais_bonus', "Carte d'étudiant : Gratuite\nTote : Gratuite\nAssurance : Gratuite") }}</textarea>
                    <small class="text-muted">Chaque ligne sera affichée avec ✅</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">📞 Contact / Besoin d'Aide</h5>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="enable_contact" name="enable_contact" value="1" 
                       {{ \App\Models\Setting::get('admission_enable_contact', '1') == '1' ? 'checked' : '' }}>
                <label class="form-check-label text-white" for="enable_contact">Afficher</label>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="admission_contact_title" class="form-label">Titre de la section</label>
                <input type="text" class="form-control" id="admission_contact_title" name="admission_contact_title" 
                       value="{{ \App\Models\Setting::get('admission_contact_title', 'Besoin d\'Aide ?') }}">
            </div>
            <div class="mb-3">
                <label for="admission_contact_text" class="form-label">Texte d'introduction</label>
                <input type="text" class="form-control" id="admission_contact_text" name="admission_contact_text" 
                       value="{{ \App\Models\Setting::get('admission_contact_text', 'Contactez-nous :') }}">
            </div>
        </div>
    </div>

    <!-- PDF du dossier d'information -->
    <div class="card mb-4">
        <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
            <h5 class="mb-0">📄 PDF du Dossier d'Information</h5>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="enable_pdf" name="enable_pdf" value="1" 
                       {{ \App\Models\Setting::get('admission_enable_pdf', '1') == '1' ? 'checked' : '' }}>
                <label class="form-check-label" for="enable_pdf">Afficher le bouton</label>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="admission_pdf_file" class="form-label">Fichier PDF (optionnel - si non fourni, le PDF sera généré automatiquement)</label>
                @php
                    $pdfFile = \App\Models\Setting::get('admission_pdf_file', '');
                @endphp
                @if($pdfFile)
                    <div class="mb-2">
                        <div class="alert alert-info">
                            <strong>PDF actuellement uploadé:</strong> {{ $pdfFile }}
                        </div>
                        <a href="{{ asset('storage/' . $pdfFile) }}" target="_blank" class="btn btn-sm btn-outline-primary me-2">
                            📄 Voir le PDF actuel
                        </a>
                        <a href="{{ route('admission.download-pdf') }}" target="_blank" class="btn btn-sm btn-outline-success">
                            📥 Tester le téléchargement
                        </a>
                    </div>
                @else
                    <div class="alert alert-warning">
                        <small>Aucun PDF uploadé. Le PDF sera généré automatiquement à partir des données ci-dessous.</small>
                    </div>
                @endif
                <input type="file" class="form-control" id="admission_pdf_file" name="admission_pdf_file" accept=".pdf">
                <small class="text-muted">Si vous uploadez un PDF, il sera utilisé au lieu de la génération automatique</small>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary btn-lg">
            💾 Enregistrer les modifications
        </button>
    </div>
</form>
@endsection

