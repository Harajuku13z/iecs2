@extends('layouts.admin')

@section('title', 'Contenus Page d\'Accueil')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>📝 Contenus de la Page d'Accueil</h1>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form action="{{ route('admin.home-content.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <!-- Hero Section -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">🎯 Section Hero</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="hero_title" class="form-label">Titre Principal</label>
                <input type="text" class="form-control" id="hero_title" name="hero_title" 
                       value="{{ \App\Models\Setting::get('hero_title', 'Façonnons l\'Avenir de l\'Excellence') }}">
            </div>
            <div class="mb-3">
                <label for="hero_subtitle" class="form-label">Sous-titre</label>
                <input type="text" class="form-control" id="hero_subtitle" name="hero_subtitle" 
                       value="{{ \App\Models\Setting::get('hero_subtitle', 'Institut d\'Enseignement Supérieur de la Côte Africaine') }}">
            </div>
            <div class="mb-3">
                <label for="hero_image" class="form-label">Image de fond</label>
                @php
                    $heroImage = \App\Models\Setting::get('hero_image', '');
                @endphp
                @if($heroImage)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $heroImage) }}" alt="Image Hero" 
                             style="max-width: 300px; max-height: 200px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd;">
                        <p class="text-muted mt-1"><small>Image actuelle</small></p>
                    </div>
                @endif
                <input type="file" class="form-control" id="hero_image" name="hero_image" accept="image/*">
                <small class="text-muted">Format: JPG, PNG, GIF. Max: 2MB</small>
            </div>
        </div>
    </div>
    
    <!-- About Section -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">📖 Section À Propos</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="about_title" class="form-label">Titre</label>
                <input type="text" class="form-control" id="about_title" name="about_title" 
                       value="{{ \App\Models\Setting::get('about_title', 'À Propos de l\'IESCA') }}">
            </div>
            <div class="mb-3">
                <label for="about_text1" class="form-label">Premier Paragraphe</label>
                <textarea class="form-control" id="about_text1" name="about_text1" rows="3">{{ \App\Models\Setting::get('about_text1', 'L\'Institut d\'Enseignement Supérieur de la Côte Africaine (IESCA) est un établissement d\'excellence situé au 112, Avenue de France (Poto poto), dédié à la formation de leaders et d\'innovateurs.') }}</textarea>
            </div>
            <div class="mb-3">
                <label for="about_text2" class="form-label">Deuxième Paragraphe</label>
                <textarea class="form-control" id="about_text2" name="about_text2" rows="3">{{ \App\Models\Setting::get('about_text2', 'Nous offrons des formations de qualité en Licence dans 4 domaines clés : Sciences et Administration des Affaires, Génie Informatique, Sciences Juridiques et Sciences Commerciales.') }}</textarea>
            </div>
            <div class="mb-3">
                <label for="about_image" class="form-label">Image</label>
                @php
                    $aboutImage = \App\Models\Setting::get('about_image', '');
                @endphp
                @if($aboutImage)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $aboutImage) }}" alt="Image À Propos" 
                             style="max-width: 300px; max-height: 200px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd;">
                        <p class="text-muted mt-1"><small>Image actuelle</small></p>
                    </div>
                @endif
                <input type="file" class="form-control" id="about_image" name="about_image" accept="image/*">
                <small class="text-muted">Format: JPG, PNG, GIF. Max: 2MB</small>
            </div>
        </div>
    </div>
    
    <!-- Atouts -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">⭐ Atouts de l'IESCA</h5>
        </div>
        <div class="card-body">
            @php
                $atouts = [
                    ['icon' => '💻', 'title' => 'Salle d\'Informatique', 'description' => 'Équipements modernes et performants'],
                    ['icon' => '📚', 'title' => 'Bibliothèque', 'description' => 'Ressources académiques complètes'],
                    ['icon' => '❄️', 'title' => 'Classes Climatisées', 'description' => 'Confort optimal pour l\'apprentissage'],
                    ['icon' => '👨‍🏫', 'title' => 'Formation Complète', 'description' => 'Cours théoriques et pratiques'],
                    ['icon' => '📹', 'title' => 'Caméras de Surveillance', 'description' => 'Sécurité assurée 24/7'],
                    ['icon' => '🏢', 'title' => 'Stage Garanti', 'description' => 'En fin de formation'],
                ];
            @endphp
            
            @foreach($atouts as $index => $atout)
                <div class="border rounded p-3 mb-3">
                    <h6>Atout {{ $index + 1 }}</h6>
                    <div class="row">
                        <div class="col-md-2 mb-2">
                            <label class="form-label">Icône</label>
                            <input type="text" class="form-control" 
                                   name="about_feature_{{ $index + 1 }}_icon" 
                                   value="{{ \App\Models\Setting::get('about_feature_' . ($index + 1) . '_icon', $atout['icon']) }}">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Titre</label>
                            <input type="text" class="form-control" 
                                   name="about_feature_{{ $index + 1 }}_title" 
                                   value="{{ \App\Models\Setting::get('about_feature_' . ($index + 1) . '_title', $atout['title']) }}">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Description</label>
                            <input type="text" class="form-control" 
                                   name="about_feature_{{ $index + 1 }}_description" 
                                   value="{{ \App\Models\Setting::get('about_feature_' . ($index + 1) . '_description', $atout['description']) }}">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
    <!-- Filières Section -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">🎓 Section Filières</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="filieres_title" class="form-label">Titre</label>
                <input type="text" class="form-control" id="filieres_title" name="filieres_title" 
                       value="{{ \App\Models\Setting::get('filieres_title', 'Découvrez nos formations d\'excellence') }}">
            </div>
        </div>
    </div>
    
    <!-- Processus d'Admission Section -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">📋 Section Processus d'Admission</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="admission_process_title" class="form-label">Titre</label>
                <input type="text" class="form-control" id="admission_process_title" name="admission_process_title" 
                       value="{{ \App\Models\Setting::get('admission_process_title', 'Processus d\'Admission') }}">
            </div>
            <div class="mb-3">
                <label for="admission_process_intro" class="form-label">Introduction</label>
                <input type="text" class="form-control" id="admission_process_intro" name="admission_process_intro" 
                       value="{{ \App\Models\Setting::get('admission_process_intro', 'Quatre étapes simples pour rejoindre l\'excellence à l\'IESCA') }}">
            </div>
            
            <hr class="my-4">
            
            <h6 class="mb-3">Les 4 Étapes</h6>
            @for($i = 1; $i <= 4; $i++)
                <div class="border rounded p-3 mb-3">
                    <h6 class="mb-3">Étape {{ $i }}</h6>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Titre</label>
                            <input type="text" class="form-control" 
                                   name="admission_step_{{ $i }}_title" 
                                   value="{{ \App\Models\Setting::get('admission_step_' . $i . '_title', ['Inscription en Ligne', 'Vérification Administrative', 'Évaluation du Comité', 'Décision d\'Admission'][$i-1]) }}">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Description</label>
                            <input type="text" class="form-control" 
                                   name="admission_step_{{ $i }}_description" 
                                   value="{{ \App\Models\Setting::get('admission_step_' . $i . '_description', ['Créez votre compte et soumettez votre dossier de candidature en quelques clics.', 'Notre équipe examine votre dossier sous 48h.', 'Le comité d\'admission étudie votre profil académique.', 'Recevez votre décision par email.'][$i-1]) }}">
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </div>
    
    <!-- Section CTA -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">🎯 Section CTA (Prêt à Rejoindre l'Excellence ?)</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="cta_title" class="form-label">Titre</label>
                <input type="text" class="form-control" id="cta_title" name="cta_title" 
                       value="{{ \App\Models\Setting::get('cta_title', 'Prêt à Rejoindre l\'Excellence ?') }}">
            </div>
            <div class="mb-3">
                <label for="cta_subtitle" class="form-label">Sous-titre</label>
                <input type="text" class="form-control" id="cta_subtitle" name="cta_subtitle" 
                       value="{{ \App\Models\Setting::get('cta_subtitle', 'Les inscriptions sont ouvertes. Commencez votre parcours vers le succès.') }}">
            </div>
            <div class="mb-3">
                <label for="cta_background_image" class="form-label">Image de fond</label>
                @php
                    $ctaBgImage = \App\Models\Setting::get('cta_background_image', '');
                @endphp
                @if($ctaBgImage)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $ctaBgImage) }}" alt="Image CTA" 
                             style="max-width: 300px; max-height: 200px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd;">
                        <p class="text-muted mt-1"><small>Image actuelle</small></p>
                    </div>
                @endif
                <input type="file" class="form-control" id="cta_background_image" name="cta_background_image" accept="image/*">
                <small class="text-muted">Format: JPG, PNG, GIF. Max: 2MB</small>
            </div>
        </div>
    </div>
    
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">💾 Enregistrer les modifications</button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Annuler</a>
    </div>
</form>
@endsection

