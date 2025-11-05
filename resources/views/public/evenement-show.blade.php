@extends('layouts.app')

@section('title', $evenement->titre . ' - IESCA')

@section('content')
<style>
.event-show-hero {
    background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-black) 100%);
    color: white;
    padding: 4rem 0;
    text-align: center;
}

.event-show-hero h1 {
    font-size: 2.5rem;
    font-weight: 900;
    margin-bottom: 1rem;
}

.event-content {
    background: white;
    border-radius: 8px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
}

.event-image-full {
    width: 100%;
    max-height: 500px;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 2rem;
}

.event-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: var(--color-light);
    border-radius: 8px;
}

.event-meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.event-meta-item strong {
    color: var(--color-primary);
}
</style>

<div class="event-show-hero">
    <div class="container">
        <h1>{{ $evenement->titre }}</h1>
        <p class="lead">{{ $evenement->type ?? 'Événement IESCA' }}</p>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="event-content">
                @if($evenement->image)
                    <img src="{{ asset('storage/' . $evenement->image) }}" alt="{{ $evenement->titre }}" class="event-image-full">
                @endif
                
                <div class="event-meta">
                    <div class="event-meta-item">
                        <strong>📅 Date:</strong>
                        <span>{{ \Carbon\Carbon::parse($evenement->date_debut)->format('d M Y') }}</span>
                        @if($evenement->date_fin && $evenement->date_fin != $evenement->date_debut)
                            <span> - {{ \Carbon\Carbon::parse($evenement->date_fin)->format('d M Y') }}</span>
                        @endif
                    </div>
                    @if($evenement->lieu)
                        <div class="event-meta-item">
                            <strong>📍 Lieu:</strong>
                            <span>{{ $evenement->lieu }}</span>
                        </div>
                    @endif
                    @if($evenement->type)
                        <div class="event-meta-item">
                            <strong>🏷️ Type:</strong>
                            <span>{{ $evenement->type }}</span>
                        </div>
                    @endif
                </div>

                <div class="event-description-full">
                    {!! nl2br(e($evenement->description)) !!}
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="event-content">
                <h5 class="mb-3">Autres événements</h5>
                @php
                    $autresEvenements = \App\Models\Evenement::where('publie', true)
                        ->where('id', '!=', $evenement->id)
                        ->orderBy('date_debut', 'desc')
                        ->take(5)
                        ->get();
                @endphp
                @if($autresEvenements->count() > 0)
                    @foreach($autresEvenements as $autre)
                        <div class="mb-3 pb-3 border-bottom">
                            <a href="{{ route('evenements.show', $autre) }}" class="text-decoration-none">
                                <strong style="color: var(--color-black);">{{ $autre->titre }}</strong>
                            </a>
                            <div class="small text-muted mt-1">
                                {{ \Carbon\Carbon::parse($autre->date_debut)->format('d M Y') }}
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">Aucun autre événement disponible.</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="text-center mt-4">
        <a href="{{ route('evenements') }}" class="btn" style="background: var(--color-primary); color: white; border-radius: 8px;">
            ← Retour aux événements
        </a>
    </div>
</div>
@endsection

