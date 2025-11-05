@extends('layouts.admin')

@section('title', 'Semestres - ' . $classe->nom)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1>📅 Semestres de la Classe</h1>
        <p class="text-muted mb-0">
            <strong>{{ $classe->nom }}</strong> - 
            {{ optional($classe->filiere)->nom }} - 
            {{ optional($classe->niveau)->nom }}
        </p>
    </div>
    <div>
        <a href="{{ route('admin.calendrier-cours.index') }}" class="btn btn-secondary">
            ← Retour
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

@if($semestres->count() > 0)
    <div class="card">
        <div class="card-body">
            <div class="row g-3">
                @foreach($semestres as $semestre)
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-4">
                                <h4 class="mb-3">
                                    <span class="badge bg-primary" style="font-size: 1.25rem; padding: 0.75rem 1.5rem;">
                                        {{ $semestre['nom'] }}
                                    </span>
                                </h4>
                                <p class="text-muted mb-3">
                                    <strong>{{ $semestre['nb_cours'] }}</strong> cours
                                </p>
                                <a href="{{ route('admin.calendrier-cours.show-semestre', ['classe' => $classe->id, 'semestre' => $semestre['nom']]) }}" class="btn btn-primary">
                                    📅 Voir le calendrier
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@else
    <div class="card">
        <div class="card-body text-center py-5">
            <p class="text-muted mb-3">Aucun semestre dans le calendrier pour cette classe.</p>
            <a href="{{ route('admin.calendrier-cours.create') }}" class="btn btn-primary">
                ➕ Ajouter des cours
            </a>
        </div>
    </div>
@endif
@endsection



