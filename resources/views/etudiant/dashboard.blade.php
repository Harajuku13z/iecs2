@extends('layouts.student')

@section('student_content')
<style>
.student-hero {
    background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
    color: white;
    padding: 2.5rem 1.25rem;
    border-radius: 12px;
    margin-bottom: 1.5rem;
}
.calendar-table {
    width: 100%;
    border-collapse: collapse;
}

.calendar-table th {
    background: var(--color-primary);
    color: white;
    padding: 1rem;
    text-align: center;
    font-weight: 600;
    font-size: 0.9rem;
}

.calendar-table td {
    padding: 0.75rem;
    border: 1px solid #e0e0e0;
    vertical-align: top;
}

.calendar-day {
    min-height: 80px;
}

.cours-item {
    background: var(--color-light);
    padding: 0.5rem;
    margin-bottom: 0.5rem;
    border-radius: 6px;
    border-left: 3px solid var(--color-primary);
    font-size: 0.85rem;
}

.cours-time {
    font-weight: 600;
    color: var(--color-primary);
    margin-bottom: 0.25rem;
    font-size: 0.8rem;
}

.cours-name {
    font-weight: 600;
    margin-bottom: 0.25rem;
    font-size: 0.85rem;
}

.cours-details {
    font-size: 0.75rem;
    color: #666;
}

.note-card {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.note-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--color-primary);
}
</style>

<div class="student-hero">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3 class="mb-1">Espace Étudiant</h3>
            <div style="opacity:.9; font-weight:600;">
                Bienvenue, {{ $user->name }} 👋
            </div>
            <div style="opacity:.9;">
                @if($classe)
                    {{ $classe->nom }} • {{ optional($classe->filiere)->nom }}
                @else
                    Classe non définie
                @endif
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('profile.edit') }}" class="btn btn-light btn-sm" style="color: var(--color-primary); font-weight:700;">Modifier mon profil</a>
        </div>
    </div>
    @if(session('error'))
        <div class="alert alert-warning alert-dismissible fade show mt-3 mb-0" role="alert" style="background: rgba(255,255,255,.15); color:#fff; border:1px solid rgba(255,255,255,.25);">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" style="filter: invert(1);"></button>
        </div>
    @endif
    </div>



<!-- Statistiques -->
<div class="container px-0 px-lg-2">
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm" style="border-radius:12px;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Mes Cours</div>
                        <div class="h4 mb-0">{{ $cours->count() }}</div>
                    </div>
                    <div style="font-size: 2rem;">📘</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm" style="border-radius:12px;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Mes Notes</div>
                        <div class="h4 mb-0">{{ $notes->count() }}</div>
                    </div>
                    <div style="font-size: 2rem;">📊</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm" style="border-radius:12px;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Ressources</div>
                        <div class="h4 mb-0">{{ $ressources->count() }}</div>
                    </div>
                    <div style="font-size: 2rem;">📂</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm" style="border-radius:12px;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Événements</div>
                        <div class="h4 mb-0">{{ $evenements->count() }}</div>
                    </div>
                    <div style="font-size: 2rem;">📅</div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Calendrier des cours -->
@if($classe && $calendrier->count() > 0)
<div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
    <div class="card-header bg-white">
        <h5 class="mb-0">📅 Calendrier des Cours</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="calendar-table">
                <thead>
                    <tr>
                        <th>Lundi</th>
                        <th>Mardi</th>
                        <th>Mercredi</th>
                        <th>Jeudi</th>
                        <th>Vendredi</th>
                        <th>Samedi</th>
                        <th>Dimanche</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @php
                            $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
                            $coursParJour = $calendrier->groupBy('jour_semaine');
                        @endphp
                        @foreach($jours as $jour)
                            <td class="calendar-day">
                                @if(isset($coursParJour[$jour]))
                                    @foreach($coursParJour[$jour] as $coursItem)
                                        <div class="cours-item">
                                            <div class="cours-time">
                                                {{ date('H:i', strtotime($coursItem->heure_debut)) }} - {{ date('H:i', strtotime($coursItem->heure_fin)) }}
                                            </div>
                                            <div class="cours-name">
                                                {{ $coursItem->cours ? $coursItem->cours->nom : ($coursItem->description ?? 'Cours') }}
                                            </div>
                                            @if($coursItem->salle)
                                                <div class="cours-details">📍 {{ $coursItem->salle }}</div>
                                            @endif
                                            @if($coursItem->enseignant)
                                                <div class="cours-details">👨‍🏫 {{ $coursItem->enseignant }}</div>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<div class="container px-0 px-lg-2">
<div class="row g-4">
    <!-- Mes notes -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100" style="border-radius:12px;">
            <div class="card-header bg-white">
                <strong>📊 Mes Notes</strong>
            </div>
            <div class="card-body">
                @if($notes->count() > 0)
                    @foreach($notes as $note)
                        <div class="note-card">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="fw-bold">{{ $note->cours->nom ?? 'Cours' }}</div>
                                    <small class="text-muted">{{ $note->type_evaluation ?? 'Évaluation' }}</small>
                                    @if($note->cours)
                                        <div class="mt-1"><small>Coef: {{ $note->cours->coefficient }}</small></div>
                                    @endif
                                </div>
                                <div class="text-end">
                                    <div class="note-value">{{ number_format($note->note, 2) }}/20</div>
</div>
</div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted mb-0">Aucune note disponible pour le moment.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Mes cours -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100" style="border-radius:12px;">
            <div class="card-header bg-white">
                <strong>📚 Mes Cours</strong>
            </div>
            <div class="card-body">
                @if($cours->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($cours as $c)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $c->nom }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $c->code }}</small>
                                    </div>
                                    <span class="badge" style="background: var(--color-primary); color: white;">
                                        Coef {{ $c->coefficient }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">Aucun cours assigné pour le moment.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Événements à venir -->
@if($evenements->count() > 0)
<div class="container px-0 px-lg-2">
<div class="card border-0 shadow-sm mt-4" style="border-radius:12px;">
    <div class="card-header bg-white">
        <strong>📅 Événements à Venir</strong>
    </div>
    <div class="card-body">
        <div class="row g-3">
            @foreach($evenements as $event)
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        @if($event->image)
                            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->titre }}" 
                                 style="height: 150px; object-fit: cover; border-radius: 8px 8px 0 0;">
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
                            <a href="{{ route('evenements.show', $event) }}" class="btn btn-sm" style="background: var(--color-primary); color: white; border-radius: 8px;">
                                En savoir plus
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
</div>
@endif

<!-- Ressources pédagogiques -->
@if($ressources->count() > 0)
<div class="container px-0 px-lg-2">
<div class="card border-0 shadow-sm mt-4" style="border-radius:12px;">
    <div class="card-header bg-white">
        <strong>📁 Ressources Pédagogiques</strong>
    </div>
    <div class="card-body">
        <div class="list-group list-group-flush">
            @foreach($ressources as $ressource)
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $ressource->titre }}</strong>
                            <br>
                            <small class="text-muted">{{ $ressource->type }}</small>
                        </div>
                        @if($ressource->cours)
                            <span class="badge bg-secondary">{{ $ressource->cours->nom }}</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
</div>
@endif
@endsection




