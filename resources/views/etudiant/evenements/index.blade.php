@extends('layouts.student')

@section('student_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>📅 Événements</h3>
</div>

<!-- Événements à venir -->
<div class="mb-5">
    <h5 class="mb-3">Événements à Venir</h5>
    @if($evenementsAVenir->count() > 0)
        <div class="row g-4">
            @foreach($evenementsAVenir as $event)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        @if($event->image)
                            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->titre }}" 
                                 style="height: 200px; object-fit: cover; border-radius: 8px 8px 0 0;">
                        @endif
                        <div class="card-body">
                            <div class="text-muted small mb-2">
                                {{ optional($event->date_debut)->format('d M Y') }}
                                @if($event->lieu)
                                    • 📍 {{ $event->lieu }}
                                @endif
                            </div>
                            <h6 class="card-title">{{ $event->titre }}</h6>
                            <p class="card-text small">{{ Str::limit($event->description, 100) }}</p>
                            <a href="{{ route('etudiant.evenements.show', $event->id) }}" class="btn btn-sm btn-primary">
                                En savoir plus
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-3">
            {{ $evenementsAVenir->links() }}
        </div>
    @else
        <div class="alert alert-info">
            Aucun événement à venir.
        </div>
    @endif
</div>

<!-- Événements passés -->
<div class="mb-5">
    <h5 class="mb-3">Événements Passés</h5>
    @if($evenementsPasses->count() > 0)
        <div class="row g-4">
            @foreach($evenementsPasses as $event)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100 opacity-75">
                        @if($event->image)
                            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->titre }}" 
                                 style="height: 200px; object-fit: cover; border-radius: 8px 8px 0 0;">
                        @endif
                        <div class="card-body">
                            <div class="text-muted small mb-2">
                                {{ optional($event->date_debut)->format('d M Y') }}
                                @if($event->lieu)
                                    • 📍 {{ $event->lieu }}
                                @endif
                            </div>
                            <h6 class="card-title">{{ $event->titre }}</h6>
                            <p class="card-text small">{{ Str::limit($event->description, 100) }}</p>
                            <a href="{{ route('etudiant.evenements.show', $event->id) }}" class="btn btn-sm btn-outline-secondary">
                                Voir détails
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-3">
            {{ $evenementsPasses->links() }}
        </div>
    @else
        <div class="alert alert-info">
            Aucun événement passé.
        </div>
    @endif
</div>
@endsection

