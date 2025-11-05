@extends('layouts.student')

@section('student_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>📊 Mes Notes</h3>
</div>

@if($notes->count() > 0)
    <!-- Statistiques générales -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <h5 class="text-muted mb-1">Moyenne Générale</h5>
                    <h2 class="mb-0" style="color: var(--color-primary);">{{ number_format($moyenneGenerale, 2) }}/20</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <h5 class="text-muted mb-1">Total Notes</h5>
                    <h2 class="mb-0" style="color: var(--color-primary);">{{ $notes->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <h5 class="text-muted mb-1">Cours Évalués</h5>
                    <h2 class="mb-0" style="color: var(--color-primary);">{{ $notesParCours->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Notes par cours -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Notes par Cours</h5>
        </div>
        <div class="card-body">
            @foreach($notesParCours as $coursId => $notesCours)
                @php
                    $cours = $notesCours->first()->cours;
                    $moyenneCours = $notesCours->avg('note');
                @endphp
                <div class="mb-4 pb-4 border-bottom">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="mb-1">{{ $cours->nom ?? 'Cours' }}</h6>
                            <small class="text-muted">Code: {{ $cours->code ?? 'N/A' }} | Coef: {{ $cours->coefficient ?? 'N/A' }}</small>
                        </div>
                        <div class="text-end">
                            <div class="h5 mb-0" style="color: var(--color-primary);">{{ number_format($moyenneCours, 2) }}/20</div>
                            <small class="text-muted">Moyenne</small>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Type d'évaluation</th>
                                    <th>Note</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notesCours as $note)
                                    <tr>
                                        <td>{{ $note->type_evaluation ?? 'Évaluation' }}</td>
                                        <td>
                                            <strong class="{{ $note->note >= 10 ? 'text-success' : 'text-danger' }}">
                                                {{ number_format($note->note, 2) }}/20
                                            </strong>
                                        </td>
                                        <td>{{ $note->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Statistiques par type -->
    @if($statistiquesParType->count() > 0)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Statistiques par Type d'Évaluation</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach($statistiquesParType as $type => $stats)
                        <div class="col-md-4">
                            <div class="card border">
                                <div class="card-body">
                                    <h6 class="mb-3">{{ $type ?? 'Non spécifié' }}</h6>
                                    <div class="mb-2">
                                        <small class="text-muted">Nombre:</small>
                                        <strong>{{ $stats['count'] }}</strong>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Moyenne:</small>
                                        <strong style="color: var(--color-primary);">{{ number_format($stats['moyenne'], 2) }}/20</strong>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Min:</small>
                                        <strong>{{ number_format($stats['min'], 2) }}/20</strong>
                                    </div>
                                    <div>
                                        <small class="text-muted">Max:</small>
                                        <strong>{{ number_format($stats['max'], 2) }}/20</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@else
    <div class="alert alert-info">
        Aucune note disponible pour le moment.
    </div>
@endif
@endsection

