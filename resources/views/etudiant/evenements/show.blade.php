@extends('layouts.student')

@section('student_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>{{ $evenement->titre }}</h3>
        <p class="text-muted mb-0">
            {{ optional($evenement->date_debut)->format('d M Y') }}
            @if($evenement->lieu)
                • 📍 {{ $evenement->lieu }}
            @endif
        </p>
    </div>
    <a href="{{ route('etudiant.evenements.index') }}" class="btn btn-sm btn-outline-secondary">
        ← Retour
    </a>
</div>

@if($evenement->image)
    <div class="card border-0 shadow-sm mb-4">
        <img src="{{ asset('storage/' . $evenement->image) }}" alt="{{ $evenement->titre }}" 
             class="card-img-top" style="max-height: 400px; object-fit: cover;">
    </div>
@endif

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="row mb-3">
            @if($evenement->date_debut)
                <div class="col-md-6 mb-2">
                    <strong>📅 Date de début:</strong> {{ $evenement->date_debut->format('d/m/Y à H:i') }}
                </div>
            @endif
            @if($evenement->date_fin)
                <div class="col-md-6 mb-2">
                    <strong>📅 Date de fin:</strong> {{ $evenement->date_fin->format('d/m/Y à H:i') }}
                </div>
            @endif
            @if($evenement->lieu)
                <div class="col-md-6 mb-2">
                    <strong>📍 Lieu:</strong> {{ $evenement->lieu }}
                </div>
            @endif
            @if($evenement->tags)
                <div class="col-md-6 mb-2">
                    <strong>Tags:</strong> 
                    @foreach(explode(',', $evenement->tags) as $tag)
                        <span class="badge bg-secondary">{{ trim($tag) }}</span>
                    @endforeach
                </div>
            @endif
        </div>
        
        <div class="mt-4">
            <h5>Description</h5>
            <p>{{ $evenement->description }}</p>
        </div>
    </div>
</div>

@if($evenementsSimilaires->count() > 0)
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Événements Similaires</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach($evenementsSimilaires as $event)
                    <div class="col-md-4">
                        <div class="card border">
                            @if($event->image)
                                <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->titre }}" 
                                     style="height: 150px; object-fit: cover;">
                            @endif
                            <div class="card-body">
                                <h6 class="card-title">{{ $event->titre }}</h6>
                                <p class="card-text small">{{ Str::limit($event->description, 60) }}</p>
                                <a href="{{ route('etudiant.evenements.show', $event->id) }}" class="btn btn-sm btn-outline-primary">
                                    Voir
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
@endsection

