@extends('layouts.admin')

@section('title', 'Calendrier - ' . $classe->nom . ' - ' . $semestre)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1>📅 Calendrier du Semestre</h1>
        <p class="mb-0" style="color: #333 !important;">
            <strong style="color: #000 !important;">{{ $classe->nom }}</strong> - 
            {{ optional($classe->filiere)->nom }} - 
            {{ optional($classe->niveau)->nom }} - 
            <span class="badge bg-primary">{{ $semestre }}</span>
        </p>
    </div>
    <div>
        <a href="{{ route('admin.calendrier-cours.show', $classe) }}" class="btn btn-secondary">
            ← Retour aux semestres
        </a>
        <a href="{{ route('admin.calendrier-cours.create') }}" class="btn btn-primary">
            ➕ Ajouter des cours
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($calendrier->count() > 0)
    <!-- Vue Calendrier avec dates réelles -->
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">📅 Vue Calendrier Hebdomadaire</h5>
                <div>
                    <button class="btn btn-sm btn-outline-secondary" onclick="previousWeek()">← Semaine précédente</button>
                    <button class="btn btn-sm btn-outline-secondary" onclick="nextWeek()">Semaine suivante →</button>
                    <button class="btn btn-sm btn-primary" onclick="currentWeek()">Aujourd'hui</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="calendrier-semaine">
                @php
                    // Utiliser la semaine passée depuis le contrôleur
                    $joursWithDates = [];
                    $joursOrder = ['Lundi' => 1, 'Mardi' => 2, 'Mercredi' => 3, 'Jeudi' => 4, 'Vendredi' => 5, 'Samedi' => 6, 'Dimanche' => 7];
                    
                    foreach ($joursOrder as $jour => $order) {
                        $date = $startOfWeek->copy()->addDays($order - 1);
                        $joursWithDates[$jour] = $date;
                    }
                @endphp
                
                <div class="table-responsive">
                    <table class="table table-bordered" style="min-height: 600px;">
                        <thead>
                            <tr>
                                @foreach($joursOrder as $jour => $order)
                                    @php
                                        $date = $joursWithDates[$jour];
                                        $coursDuJour = $calendrierParJour[$jour] ?? collect();
                                    @endphp
                                    <th class="text-center" style="width: 14.28%; background: linear-gradient(135deg, rgba(166, 96, 96, 0.15), rgba(158, 90, 89, 0.15)); border: 2px solid var(--color-primary); color: #000 !important; padding: 1rem;">
                                        <div class="fw-bold" style="color: #000 !important;">{{ $jour }}</div>
                                        <div class="small" style="color: #000 !important;">{{ $date->format('d/m/Y') }}</div>
                                        @if($date->isToday())
                                            <span class="badge mt-1" style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); color: white !important; padding: 0.25rem 0.5rem;">Aujourd'hui</span>
                                        @endif
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="height: 500px; vertical-align: top;">
                                @foreach($joursOrder as $jour => $order)
                                    @php
                                        $coursDuJour = $calendrierParJour[$jour] ?? collect();
                                    @endphp
                                    <td style="padding: 0.5rem; vertical-align: top;">
                                        @if($coursDuJour->count() > 0)
                                            <div class="d-flex flex-column gap-2">
                                                @foreach($coursDuJour->sortBy('heure_debut') as $cal)
                                                    <div class="calendrier-cours-item-small" style="background: white; border: 2px solid var(--color-primary); border-radius: 8px; padding: 0.75rem; transition: all 0.3s ease; font-size: 0.85rem;">
                                                        <div class="fw-bold mb-1" style="color: var(--color-primary);">
                                                            {{ date('H:i', strtotime($cal->heure_debut)) }} - {{ date('H:i', strtotime($cal->heure_fin)) }}
                                                        </div>
                                                        <div class="fw-semibold mb-1" style="font-size: 0.9rem; color: #000 !important;">
                                                            {{ $cal->cours->nom ?? ($cal->description ?? 'Cours') }}
                                                        </div>
                                                        @if($cal->salle)
                                                            <div class="small" style="color: #333 !important;">📍 {{ $cal->salle }}</div>
                                                        @endif
                                                        @if($cal->enseignant)
                                                            <div class="small" style="color: #333 !important;">👨‍🏫 {{ $cal->enseignant }}</div>
                                                        @endif
                                                        <div class="mt-2 d-flex gap-1">
                                                            <a href="{{ route('admin.calendrier-cours.edit', $cal) }}" class="btn btn-xs btn-outline-primary" style="font-size: 0.7rem; padding: 0.2rem 0.4rem;">
                                                                ✏️
                                                            </a>
                                                            <form action="{{ route('admin.calendrier-cours.destroy', $cal) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Supprimer ce cours ?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-xs btn-outline-danger" style="font-size: 0.7rem; padding: 0.2rem 0.4rem;">
                                                                    🗑️
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-center small p-3" style="color: #666 !important;">
                                                Aucun cours
                                            </div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Vue Liste par jour -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">📋 Vue Liste par Jour</h5>
        </div>
        <div class="card-body">
            <div class="calendrier-semaine">
                @foreach($calendrierParJour as $jour => $coursJour)
                    <div class="mb-4 pb-4 border-bottom">
                        <div class="d-flex align-items-center mb-3">
                            <h4 class="mb-0 me-3">
                                <span class="badge" style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); color: white; padding: 0.75rem 1.5rem; font-size: 1.1rem;">
                                    {{ $jour }}
                                </span>
                            </h4>
                            <span style="color: #666 !important;">{{ $coursJour->count() }} cours</span>
                        </div>
                        
                        <div class="row g-3">
                            @foreach($coursJour->sortBy('heure_debut') as $cal)
                                <div class="col-md-6 col-lg-4">
                                    <div class="calendrier-cours-item" style="background: white; border: 2px solid var(--color-primary); border-radius: 12px; padding: 1.25rem; transition: all 0.3s ease;">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div class="heure-badge" style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); color: white; padding: 0.4rem 0.8rem; border-radius: 6px; font-weight: 600; font-size: 0.85rem;">
                                                {{ date('H:i', strtotime($cal->heure_debut)) }} - {{ date('H:i', strtotime($cal->heure_fin)) }}
                                            </div>
                                        </div>
                                        
                                        <h6 class="mb-2" style="color: var(--color-primary); font-weight: 700; font-size: 1rem;">
                                            {{ $cal->cours->nom ?? ($cal->description ?? 'Cours non spécifié') }}
                                        </h6>
                                        
                                        @if($cal->cours)
                                            <p class="mb-2" style="color: #333 !important; font-size: 0.85rem;">
                                                <strong style="color: #000 !important;">Code:</strong> {{ $cal->cours->code }}
                                            </p>
                                        @endif
                                        
                                        @if($cal->salle)
                                            <p class="mb-2" style="color: #333 !important; font-size: 0.85rem;">
                                                <strong style="color: #000 !important;">📍 Salle:</strong> {{ $cal->salle }}
                                            </p>
                                        @endif
                                        
                                        @if($cal->enseignant)
                                            <p class="mb-2" style="color: #333 !important; font-size: 0.85rem;">
                                                <strong style="color: #000 !important;">👨‍🏫 Enseignant:</strong> {{ $cal->enseignant }}
                                            </p>
                                        @endif
                                        
                                        <div class="d-flex gap-2 mt-3">
                                            <a href="{{ route('admin.calendrier-cours.edit', $cal) }}" class="btn btn-sm btn-outline-primary">
                                                ✏️ Modifier
                                            </a>
                                            <form action="{{ route('admin.calendrier-cours.destroy', $cal) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce cours du calendrier ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    🗑️ Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <!-- Vue tableau alternative -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Vue Tableau</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Jour</th>
                            <th>Heure</th>
                            <th>Cours</th>
                            <th>Salle</th>
                            <th>Enseignant</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($calendrierParJour as $jour => $coursJour)
                            @foreach($coursJour->sortBy('heure_debut') as $cal)
                                <tr>
                                    <td><strong style="color: #000 !important;">{{ $jour }}</strong></td>
                                    <td>
                                        <strong style="color: #000 !important;">{{ date('H:i', strtotime($cal->heure_debut)) }} - {{ date('H:i', strtotime($cal->heure_fin)) }}</strong>
                                    </td>
                                    <td>
                                        <strong style="color: #000 !important;">{{ $cal->cours->nom ?? ($cal->description ?? 'Cours non spécifié') }}</strong>
                                        @if($cal->cours)
                                            <br><small style="color: #666 !important;">{{ $cal->cours->code }}</small>
                                        @endif
                                    </td>
                                    <td style="color: #000 !important;">{{ $cal->salle ?? '-' }}</td>
                                    <td style="color: #000 !important;">{{ $cal->enseignant ?? '-' }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.calendrier-cours.edit', $cal) }}" class="btn btn-sm btn-outline-primary">
                                                ✏️ Modifier
                                            </a>
                                            <form action="{{ route('admin.calendrier-cours.destroy', $cal) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce cours du calendrier ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    🗑️ Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@else
    <div class="card">
        <div class="card-body text-center py-5">
            <p class="mb-3" style="color: #666 !important;">Aucun cours dans le calendrier pour ce semestre.</p>
            <a href="{{ route('admin.calendrier-cours.create') }}" class="btn btn-primary">
                ➕ Ajouter des cours
            </a>
        </div>
    </div>
@endif

<style>
    .calendrier-cours-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(166, 96, 96, 0.25);
        border-color: var(--color-secondary) !important;
    }
    
    .calendrier-cours-item-small:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(166, 96, 96, 0.2);
        border-color: var(--color-secondary) !important;
    }
    
    .table th {
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    /* Force les textes à être noirs */
    .calendrier-cours-item-small,
    .calendrier-cours-item,
    .table td,
    .table tbody td {
        color: #000 !important;
    }
    
    .table td strong,
    .table td {
        color: #000 !important;
    }
    
    .card-body p,
    .card-body div:not(.badge):not(.btn) {
        color: #000 !important;
    }
</style>

<script>
let currentWeekStart = new Date('{{ $startOfWeek->format('Y-m-d') }}');
    
function updateCalendrier() {
    // Cette fonction sera appelée pour mettre à jour les dates du calendrier
    // Pour l'instant, on garde la vue statique basée sur la semaine actuelle
    // Pour une version complète, il faudrait faire une requête AJAX
}

function previousWeek() {
    currentWeekStart.setDate(currentWeekStart.getDate() - 7);
    location.href = '{{ route('admin.calendrier-cours.show-semestre', ['classe' => $classe->id, 'semestre' => $semestre]) }}?week=' + currentWeekStart.toISOString().split('T')[0];
}

function nextWeek() {
    currentWeekStart.setDate(currentWeekStart.getDate() + 7);
    location.href = '{{ route('admin.calendrier-cours.show-semestre', ['classe' => $classe->id, 'semestre' => $semestre]) }}?week=' + currentWeekStart.toISOString().split('T')[0];
}

function currentWeek() {
    location.href = '{{ route('admin.calendrier-cours.show-semestre', ['classe' => $classe->id, 'semestre' => $semestre]) }}';
}
</script>
@endsection



