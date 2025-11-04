@extends('layouts.admin')

@section('title', 'Paramètres du Site')

@section('content')
<h1 class="mb-4">Paramètres du Site</h1>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Logo Upload -->
            <div class="mb-4 p-4 border rounded">
                <h5 class="mb-3">🎨 Logo de l'IESCA</h5>
                <div class="mb-3">
                    @php
                        $logo = \App\Models\Setting::get('logo', '');
                    @endphp
                    @if($logo)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $logo) }}" alt="Logo actuel" style="max-height: 100px; border: 1px solid #ddd; padding: 10px; border-radius: 10px;">
                        </div>
                    @endif
                    <input type="file" class="form-control" name="logo" accept="image/*">
                    <small class="text-muted">Format recommandé: PNG ou JPG, max 2MB</small>
                </div>
            </div>

            @php
                $homepageKeys = ['logo', 'admission_process_image', 'hero_title', 'hero_subtitle', 'hero_image', 'about_title', 'about_text1', 'about_text2', 'about_image', 'about_feature_1_icon', 'about_feature_1_title', 'about_feature_1_description', 'about_feature_2_icon', 'about_feature_2_title', 'about_feature_2_description', 'about_feature_3_icon', 'about_feature_3_title', 'about_feature_3_description', 'about_feature_4_icon', 'about_feature_4_title', 'about_feature_4_description', 'about_feature_5_icon', 'about_feature_5_title', 'about_feature_5_description', 'about_feature_6_icon', 'about_feature_6_title', 'about_feature_6_description', 'filieres_title', 'admission_process_title', 'admission_process_intro', 'admission_step_1_title', 'admission_step_1_description', 'admission_step_2_title', 'admission_step_2_description', 'admission_step_3_title', 'admission_step_3_description', 'admission_step_4_title', 'admission_step_4_description', 'cta_title', 'cta_subtitle', 'cta_background_image'];
                $colorSettings = [];
                $socialSettings = [];
                $otherSettings = [];
                
                foreach($settings as $setting) {
                    if(in_array($setting->cle, $homepageKeys)) {
                        continue;
                    }
                    if(str_starts_with($setting->cle, 'color_')) {
                        $colorSettings[] = $setting;
                    } elseif(str_starts_with($setting->cle, 'social_')) {
                        $socialSettings[] = $setting;
                    } else {
                        $otherSettings[] = $setting;
                    }
                }
            @endphp

            @if(count($colorSettings) > 0)
                <div class="mb-4 p-4 border rounded">
                    <h5 class="mb-3">🎨 Couleurs du Site</h5>
                    @foreach($colorSettings as $setting)
                        <div class="mb-3">
                            <label for="{{ $setting->cle }}" class="form-label">
                                <strong>{{ ucfirst(str_replace(['color_', '_'], ['', ' '], $setting->cle)) }}</strong>
                            </label>
                            @if($setting->description)
                                <small class="text-muted d-block">{{ $setting->description }}</small>
                            @endif
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color" id="{{ $setting->cle }}" 
                                       name="{{ $setting->cle }}" value="{{ $setting->valeur }}" style="width: 80px;">
                                <input type="text" class="form-control" value="{{ $setting->valeur }}" 
                                       onchange="document.getElementById('{{ $setting->cle }}').value = this.value"
                                       oninput="this.previousElementSibling.value = this.value; this.previousElementSibling.setAttribute('name', '{{ $setting->cle }}');">
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if(count($socialSettings) > 0)
                <div class="mb-4 p-4 border rounded">
                    <h5 class="mb-3">📱 Réseaux Sociaux</h5>
                    @foreach($socialSettings as $setting)
                        <div class="mb-3">
                            <label for="{{ $setting->cle }}" class="form-label">
                                <strong>{{ ucfirst(str_replace(['social_', '_'], ['', ' '], $setting->cle)) }}</strong>
                            </label>
                            @if($setting->description)
                                <small class="text-muted d-block">{{ $setting->description }}</small>
                            @endif
                            <input type="url" class="form-control" id="{{ $setting->cle }}" 
                                   name="{{ $setting->cle }}" value="{{ $setting->valeur }}" 
                                   placeholder="https://...">
                            <small class="text-muted">Laissez vide pour masquer ce réseau social</small>
                        </div>
                    @endforeach
                </div>
            @endif

            @if(count($otherSettings) > 0)
                <div class="mb-4 p-4 border rounded">
                    <h5 class="mb-3">⚙️ Autres Paramètres</h5>
                    @foreach($otherSettings as $setting)
                        <div class="mb-3">
                            <label for="{{ $setting->cle }}" class="form-label">
                                <strong>{{ ucfirst(str_replace('_', ' ', $setting->cle)) }}</strong>
                            </label>
                            @if($setting->description)
                                <small class="text-muted d-block">{{ $setting->description }}</small>
                            @endif
                            
                            @if(in_array($setting->cle, ['homepage_title', 'banner_image']))
                                <input type="text" class="form-control" id="{{ $setting->cle }}" 
                                       name="{{ $setting->cle }}" value="{{ $setting->valeur }}">
                            @elseif($setting->cle === 'inscription_start_date')
                                <input type="date" class="form-control" id="{{ $setting->cle }}" 
                                       name="{{ $setting->cle }}" value="{{ $setting->valeur }}">
                            @elseif($setting->cle === 'frais_mensuels')
                                <input type="number" class="form-control" id="{{ $setting->cle }}" 
                                       name="{{ $setting->cle }}" value="{{ $setting->valeur }}">
                            @else
                                <textarea class="form-control" id="{{ $setting->cle }}" 
                                          name="{{ $setting->cle }}" rows="3">{{ $setting->valeur }}</textarea>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">💾 Enregistrer les modifications</button>
            </div>
        </form>
    </div>
</div>
@endsection

