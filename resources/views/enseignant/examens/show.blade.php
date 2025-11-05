@extends('layouts.enseignant')

@section('teacher_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>Examen: {{ $typeEvaluation }}</h3>
        <p class="text-muted mb-0">Cours: {{ $cours->nom }} ({{ $cours->code }})</p>
    </div>
    <a href="{{ route('enseignant.examens.index') }}" class="btn btn-sm btn-outline-secondary">← Retour</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h5 class="mb-3">Notes des Étudiants</h5>
        @if($notes->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Étudiant</th>
                            <th>Note</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($notes as $note)
                            <tr>
                                <td>{{ $note->etudiant->name ?? 'N/A' }}</td>
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
            <div class="mt-3">
                <strong>Moyenne: {{ number_format($notes->avg('note'), 2) }}/20</strong>
            </div>
        @else
            <p class="text-muted">Aucune note enregistrée pour cet examen.</p>
        @endif
    </div>
</div>
@endsection

