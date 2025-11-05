@extends('layouts.student')

@section('student_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>{{ $cours->nom }}</h3>
        <p class="text-muted mb-0">Code: {{ $cours->code }} | Coefficient: {{ $cours->coefficient }}</p>
    </div>
    <a href="{{ route('etudiant.cours.index') }}" class="btn btn-sm btn-outline-secondary">
        ← Retour
    </a>
</div>

@if($cours->description)
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h5>Description</h5>
            <p>{{ $cours->description }}</p>
        </div>
    </div>
@endif

<div class="row g-4">
    <!-- Mes notes pour ce cours -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">📊 Mes Notes</h5>
            </div>
            <div class="card-body">
                @if($notes->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Note</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notes as $note)
                                    <tr>
                                        <td>{{ $note->type_evaluation ?? 'Évaluation' }}</td>
                                        <td>
                                            <strong class="text-primary">{{ number_format($note->note, 2) }}/20</strong>
                                        </td>
                                        <td>{{ $note->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @php
                        $moyenne = $notes->avg('note');
                    @endphp
                    <div class="mt-3 p-3" style="background: var(--color-light); border-radius: 8px;">
                        <strong>Moyenne: {{ number_format($moyenne, 2) }}/20</strong>
                    </div>
                @else
                    <p class="text-muted mb-0">Aucune note pour ce cours.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Ressources -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">📁 Ressources</h5>
            </div>
            <div class="card-body">
                @if($ressources->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($ressources as $ressource)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $ressource->titre }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $ressource->type }}</small>
                                    </div>
                                    <a href="{{ route('etudiant.ressources.show', $ressource->id) }}" class="btn btn-sm btn-outline-primary">
                                        Voir
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">Aucune ressource disponible pour ce cours.</p>
                @endif
            </div>
        </div>
    </div>
</div>

@if($cours->enseignants->count() > 0)
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body">
            <h5>👨‍🏫 Enseignants</h5>
            <ul class="list-unstyled">
                @foreach($cours->enseignants as $enseignant)
                    <li>{{ $enseignant->name }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
@endsection

