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

            <!-- Admission Process Image Upload -->
            <div class="mb-4 p-4 border rounded">
                <h5 class="mb-3">📸 Image du Processus d'Admission</h5>
                <div class="mb-3">
                    @php
                        $admissionImage = \App\Models\Setting::get('admission_process_image', '');
                    @endphp
                    @if($admissionImage)
                        <div class="mb-3">
                            <p class="mb-2"><strong>Image actuelle:</strong></p>
                            <img src="{{ str_starts_with($admissionImage, 'http') ? $admissionImage : asset('storage/' . $admissionImage) }}" 
                                 alt="Image Processus d'Admission" 
                                 style="max-width: 300px; max-height: 500px; border: 1px solid #ddd; padding: 10px; border-radius: 10px; object-fit: cover;">
                            <p class="text-muted mt-2"><small>Format recommandé: 9:16 (portrait), max 5MB</small></p>
                        </div>
                    @else
                        <p class="text-muted"><small>Format recommandé: 9:16 (portrait), max 5MB. Exemple: 900x1600px</small></p>
                    @endif
                    <input type="file" class="form-control" name="admission_process_image" accept="image/*">
                    <small class="text-muted d-block mt-2">📐 Format: 9:16 (portrait vertical) - Exemple: 900x1600px</small>
                </div>
            </div>

            @foreach($settings as $setting)
                @if(in_array($setting->cle, ['logo', 'admission_process_image']))
                    @continue
                @endif
                <div class="mb-3">
                    <label for="{{ $setting->cle }}" class="form-label">
                        <strong>{{ ucfirst(str_replace('_', ' ', $setting->cle)) }}</strong>
                    </label>
                    @if($setting->description)
                        <small class="text-muted d-block">{{ $setting->description }}</small>
                    @endif
                    
                    @if(str_starts_with($setting->cle, 'color_'))
                        <div class="input-group">
                            <input type="color" class="form-control form-control-color" id="{{ $setting->cle }}" 
                                   name="{{ $setting->cle }}" value="{{ $setting->valeur }}" style="width: 80px;">
                            <input type="text" class="form-control" value="{{ $setting->valeur }}" 
                                   onchange="document.getElementById('{{ $setting->cle }}').value = this.value"
                                   oninput="this.previousElementSibling.value = this.value; this.previousElementSibling.setAttribute('name', '{{ $setting->cle }}');">
                        </div>
                    @elseif(str_starts_with($setting->cle, 'social_'))
                        <input type="url" class="form-control" id="{{ $setting->cle }}" 
                               name="{{ $setting->cle }}" value="{{ $setting->valeur }}" 
                               placeholder="https://...">
                        <small class="text-muted">Laissez vide pour masquer ce réseau social</small>
                    @elseif(in_array($setting->cle, ['homepage_title', 'banner_image']))
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

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">💾 Enregistrer les modifications</button>
            </div>
        </form>
    </div>
</div>
@endsection

