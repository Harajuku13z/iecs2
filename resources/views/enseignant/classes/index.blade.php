@extends('layouts.enseignant')

@section('teacher_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>📚 Mes Classes</h3>
</div>

@if(($classes ?? collect())->count() === 0)
    <div class="alert alert-info">Aucune classe assignée pour le moment.</div>
@else
    <div class="row g-3">
        @foreach($classes as $classe)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-1">{{ $classe->nom }}</h5>
                        <div class="text-muted small mb-2">{{ optional($classe->filiere)->nom }} • {{ optional($classe->niveau)->nom }}</div>
                        <div class="mb-2"><strong>Étudiants:</strong> {{ $classe->etudiants->count() }}</div>
                        <a href="{{ route('enseignant.classes.show', $classe) }}" class="btn btn-sm btn-outline-primary">Voir la classe</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection

