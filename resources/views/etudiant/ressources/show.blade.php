@extends('layouts.student')

@section('student_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>{{ $ressource->titre }}</h3>
        <p class="text-muted mb-0">
            Type: {{ $ressource->type }}
            @if($ressource->cours)
                | Cours: {{ $ressource->cours->nom }}
            @endif
        </p>
    </div>
    <a href="{{ route('etudiant.ressources.index') }}" class="btn btn-sm btn-outline-secondary">
        ← Retour
    </a>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Type:</strong> {{ $ressource->type }}
            </div>
            @if($ressource->cours)
                <div class="col-md-6">
                    <strong>Cours:</strong> {{ $ressource->cours->nom }}
                </div>
            @endif
        </div>
        @if($ressource->enseignant)
            <div class="mb-3">
                <strong>Enseignant:</strong> {{ $ressource->enseignant->name }}
            </div>
        @endif
        <div class="mb-3">
            <strong>Date d'ajout:</strong> {{ $ressource->created_at->format('d/m/Y à H:i') }}
        </div>
        
        @if($ressource->contenu)
            <div class="mt-4">
                <a href="{{ route('etudiant.ressources.download', $ressource->id) }}" class="btn btn-primary">
                    📥 Télécharger la ressource
                </a>
            </div>
        @endif
    </div>
</div>

@if($ressource->contenu && (strpos($ressource->contenu, '.txt') !== false || strpos($ressource->contenu, '.md') !== false))
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Aperçu</h5>
        </div>
        <div class="card-body">
            <p class="text-muted">Pour voir le contenu complet, veuillez télécharger la ressource.</p>
        </div>
    </div>
@endif
@endsection



