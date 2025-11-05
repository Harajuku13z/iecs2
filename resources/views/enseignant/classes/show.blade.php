@extends('layouts.enseignant')

@section('teacher_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">🏫 {{ $classe->nom }}</h3>
        <div class="text-muted">{{ optional($classe->filiere)->nom }} • {{ optional($classe->niveau)->nom }}</div>
    </div>
    <a href="{{ route('enseignant.classes.index') }}" class="btn btn-outline-secondary btn-sm">← Retour</a>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h5 class="mb-3">📚 Mes cours dans cette classe</h5>
                @if($coursEnseigne->count() === 0)
                    <p class="text-muted mb-0">Aucun cours assigné pour cette classe.</p>
                @else
                    <div class="list-group list-group-flush">
                        @foreach($coursEnseigne as $c)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold">{{ $c->nom }}</div>
                                    <small class="text-muted">Code: {{ $c->code }} • Coef: {{ $c->coefficient }}</small>
                                </div>
                                <a href="{{ route('enseignant.cours.show', $c) }}" class="btn btn-sm btn-outline-primary">Voir</a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h5 class="mb-3">👥 Étudiants ({{ $etudiants->count() }})</h5>
                @if($etudiants->count() === 0)
                    <p class="text-muted mb-0">Aucun étudiant enregistré dans cette classe.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($etudiants as $e)
                                    <tr>
                                        <td>{{ $e->name }}</td>
                                        <td>{{ $e->email }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
