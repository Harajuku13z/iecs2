@extends('layouts.student')

@section('student_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>📅 Calendrier des Cours</h3>
        @if($classe)
            <p class="text-muted mb-0">
                <strong>{{ $classe->nom }}</strong> - 
                {{ optional($classe->filiere)->nom }} - 
                {{ optional($classe->niveau)->nom }}
            </p>
        @endif
    </div>
    <div>
        <button class="btn btn-sm btn-outline-secondary" onclick="previousWeek()">← Semaine précédente</button>
        <button class="btn btn-sm btn-outline-secondary" onclick="nextWeek()">Semaine suivante →</button>
        <button class="btn btn-sm btn-primary" onclick="currentWeek()">Aujourd'hui</button>
    </div>
</div>

@if($message ?? false)
    <div class="alert alert-info">
        {{ $message }}
    </div>
@elseif($calendrierChronologique && $calendrierChronologique->count() > 0)
    <!-- Vue Chronologique par Date -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">📅 Vue Chronologique des Cours</h5>
        </div>
        <div class="card-body">
            @foreach($calendrierParDate as $dateStr => $coursDuJour)
                @php
                    $date = \Carbon\Carbon::parse($dateStr);
                    $isToday = $date->isToday();
                    $isPast = $date->isPast() && !$date->isToday();
                @endphp
                
                <div class="mb-4 pb-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <!-- En-tête du jour -->
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            <h4 class="mb-0">
                                <span class="badge {{ $isToday ? 'bg-primary' : ($isPast ? 'bg-secondary' : 'bg-success') }}" 
                                      style="padding: 0.75rem 1.5rem; font-size: 1.1rem;">
                                    {{ $date->format('l') }} {{ $date->format('d/m/Y') }}
                                    @if($isToday)
                                        <span class="ms-2">(Aujourd'hui)</span>
                                    @endif
                                </span>
                            </h4>
                        </div>
                        <span class="text-muted">{{ $coursDuJour->count() }} cours prévu(s)</span>
                    </div>
                    
                    <!-- Liste des cours du jour, triés par heure -->
                    <div class="row g-3">
                        @foreach($coursDuJour->sortBy('heure_debut_str') as $item)
                            <div class="col-12">
                                <div class="calendrier-cours-item" 
                                     style="background: white; border: 2px solid var(--color-primary); border-radius: 12px; padding: 1.25rem; transition: all 0.3s ease;">
                                    <div class="row align-items-center">
                                        <div class="col-md-2">
                                            <div class="heure-badge text-center" 
                                                 style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); color: white; padding: 0.75rem 1rem; border-radius: 8px; font-weight: 700; font-size: 1rem;">
                                                {{ $item['heure_debut_str'] }}<br>
                                                <small style="font-size: 0.75rem;">{{ $item['heure_fin_str'] }}</small>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-8">
                                            <h6 class="mb-2" style="color: var(--color-primary); font-weight: 700; font-size: 1.1rem;">
                                                {{ $item['cours_nom'] }}
                                            </h6>
                                            
                                            @if($item['cours_code'])
                                                <p class="mb-2" style="color: #333 !important; font-size: 0.9rem;">
                                                    <strong style="color: #000 !important;">Code:</strong> {{ $item['cours_code'] }}
                                                </p>
                                            @endif
                                            
                                            @if($item['semestre'])
                                                <span class="badge bg-info mb-2">{{ $item['semestre'] }}</span>
                                            @endif
                                            
                                            @if($item['salle'])
                                                <p class="mb-1" style="color: #333 !important; font-size: 0.9rem;">
                                                    <strong style="color: #000 !important;">📍 Salle:</strong> {{ $item['salle'] }}
                                                </p>
                                            @endif
                                            
                                            @if($item['enseignant'])
                                                <p class="mb-1" style="color: #333 !important; font-size: 0.9rem;">
                                                    <strong style="color: #000 !important;">👨‍🏫 Enseignant:</strong> {{ $item['enseignant'] }}
                                                </p>
                                            @endif
                                            
                                            @if($item['description'])
                                                <p class="mb-0 mt-2" style="color: #666 !important; font-size: 0.85rem;">
                                                    {{ $item['description'] }}
                                                </p>
                                            @endif
                                        </div>
                                        
                                        <div class="col-md-2 text-end">
                                            @if($isPast)
                                                <span class="badge bg-secondary">Passé</span>
                                            @elseif($isToday)
                                                <span class="badge bg-success">Aujourd'hui</span>
                                            @else
                                                <span class="badge bg-primary">À venir</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
    <!-- Vue Compacte Liste -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">📋 Liste Compacte</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Heure</th>
                            <th>Cours</th>
                            <th>Salle</th>
                            <th>Enseignant</th>
                            <th>Semestre</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($calendrierChronologique as $item)
                            @php
                                $date = $item['date'];
                                $isToday = $date->isToday();
                                $isPast = $date->isPast() && !$date->isToday();
                            @endphp
                            <tr class="{{ $isToday ? 'table-primary' : ($isPast ? 'table-secondary' : '') }}">
                                <td>
                                    <strong style="color: #000 !important;">{{ $item['date_formatted'] }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $item['jour_nom'] }}</small>
                                    @if($isToday)
                                        <span class="badge bg-success ms-1">Aujourd'hui</span>
                                    @endif
                                </td>
                                <td>
                                    <strong style="color: #000 !important;">
                                        {{ $item['heure_debut_str'] }} - {{ $item['heure_fin_str'] }}
                                    </strong>
                                </td>
                                <td>
                                    <strong style="color: #000 !important;">{{ $item['cours_nom'] }}</strong>
                                    @if($item['cours_code'])
                                        <br><small class="text-muted">{{ $item['cours_code'] }}</small>
                                    @endif
                                </td>
                                <td style="color: #000 !important;">{{ $item['salle'] ?? '-' }}</td>
                                <td style="color: #000 !important;">{{ $item['enseignant'] ?? '-' }}</td>
                                <td>
                                    @if($item['semestre'])
                                        <span class="badge bg-info">{{ $item['semestre'] }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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

.table th {
    background: linear-gradient(135deg, rgba(166, 96, 96, 0.15), rgba(158, 90, 89, 0.15));
    color: #000 !important;
    font-weight: 700;
    border: 2px solid var(--color-primary);
}

.table td {
    color: #000 !important;
    vertical-align: middle;
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
