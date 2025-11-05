@extends('layouts.admin')

@section('title', 'Configuration Page Événements')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>📅 Configuration Page Événements</h1>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form action="{{ route('admin.event-content.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <!-- Hero Section -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">🎯 Section Hero</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="events_hero_title" class="form-label">Titre Principal</label>
                <input type="text" class="form-control" id="events_hero_title" name="events_hero_title" 
                       value="{{ \App\Models\Setting::get('events_hero_title', '📅 Nos Événements') }}">
            </div>
            <div class="mb-3">
                <label for="events_hero_subtitle" class="form-label">Sous-titre</label>
                <input type="text" class="form-control" id="events_hero_subtitle" name="events_hero_subtitle" 
                       value="{{ \App\Models\Setting::get('events_hero_subtitle', 'Découvrez les événements et activités de l\'IESCA') }}">
            </div>
            <div class="mb-3">
                <label for="events_hero_image" class="form-label">Image de Fond</label>
                <input type="file" class="form-control" id="events_hero_image" name="events_hero_image" accept="image/*">
                <small class="text-muted">Formats acceptés: JPG, PNG, GIF, WebP. Taille max: 5MB</small>
                @php
                    $currentImage = \App\Models\Setting::get('events_hero_image', '');
                @endphp
                @if($currentImage)
                    <div class="mt-3">
                        <p class="text-muted small mb-2"><strong>Image actuelle:</strong></p>
                        <img src="{{ asset('storage/' . $currentImage) }}" alt="Image actuelle" 
                             style="max-width: 100%; max-height: 300px; border-radius: 8px; border: 2px solid #dee2e6;"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <div style="display:none; padding: 1rem; background: #f8f9fa; border-radius: 8px; color: #dc3545;">
                            <small>⚠️ Image non trouvée. Veuillez re-uploader l'image.</small>
                        </div>
                        <p class="text-muted small mt-2">Fichier: {{ $currentImage }}</p>
                    </div>
                @else
                    <div class="mt-2">
                        <small class="text-muted">Aucune image actuellement définie.</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
    </div>
</form>
@endsection

