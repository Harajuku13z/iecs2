@extends('layouts.student')

@section('student_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>📅 Calendrier des Cours</h3>
        @if($classe)
            <p class="mb-0" style="color: #333 !important;">
                <strong style="color: #000 !important;">{{ $classe->nom }}</strong> - 
                {{ optional($classe->filiere)->nom }} - 
                {{ optional($classe->niveau)->nom }}
            </p>
        @endif
    </div>
</div>

@if($message ?? false)
    <div class="alert alert-info">
        {{ $message }}
    </div>
@elseif($calendrier->count() > 0)
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
                                                        @if($cal->cours && $cal->cours->code)
                                                            <div class="small mb-1" style="color: #333 !important;">
                                                                <strong style="color: #000 !important;">Code:</strong> {{ $cal->cours->code }}
                                                            </div>
                                                        @endif
                                                        @if($cal->salle)
                                                            <div class="small" style="color: #333 !important;">📍 {{ $cal->salle }}</div>
                                                        @endif
                                                        @if($cal->enseignant)
                                                            <div class="small" style="color: #333 !important;">👨‍🏫 {{ $cal->enseignant }}</div>
                                                        @endif
                                                        @if($cal->semestre)
                                                            <div class="small mt-1">
                                                                <span class="badge bg-primary" style="font-size: 0.7rem;">{{ $cal->semestre }}</span>
                                                            </div>
                                                        @endif
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
                                            @if($cal->semestre)
                                                <span class="badge bg-primary">{{ $cal->semestre }}</span>
                                            @endif
                                        </div>
                                        
                                        <h6 class="mb-2" style="color: var(--color-primary); font-weight: 700; font-size: 1rem;">
                                            {{ $cal->cours->nom ?? ($cal->description ?? 'Cours non spécifié') }}
                                        </h6>
                                        
                                        @if($cal->cours && $cal->cours->code)
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
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@else
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <p class="text-muted mb-3">Aucun cours planifié pour le moment.</p>
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
    
function previousWeek() {
    currentWeekStart.setDate(currentWeekStart.getDate() - 7);
    location.href = '{{ route('etudiant.calendrier.index') }}?week=' + currentWeekStart.toISOString().split('T')[0];
}

function nextWeek() {
    currentWeekStart.setDate(currentWeekStart.getDate() + 7);
    location.href = '{{ route('etudiant.calendrier.index') }}?week=' + currentWeekStart.toISOString().split('T')[0];
}

function currentWeek() {
    location.href = '{{ route('etudiant.calendrier.index') }}';
}
</script>
@endsection
