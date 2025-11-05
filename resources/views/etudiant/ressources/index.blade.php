@extends('layouts.student')

@section('student_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>📁 Ressources Pédagogiques</h3>
</div>

@if($message ?? false)
    <div class="alert alert-info">
        {{ $message }}
    </div>
@elseif($ressources->count() > 0)
    <!-- Statistiques -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <h5 class="text-muted mb-1">Total Ressources</h5>
                    <h3 class="mb-0" style="color: var(--color-primary);">{{ $ressources->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <h5 class="text-muted mb-1">Par Cours</h5>
                    <h3 class="mb-0" style="color: var(--color-primary);">{{ $ressourcesParCours->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <h5 class="text-muted mb-1">Types</h5>
                    <h3 class="mb-0" style="color: var(--color-primary);">{{ $ressourcesParType->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Filtrer par cours</label>
                    <select class="form-select" id="filterCours">
                        <option value="">Tous les cours</option>
                        @foreach($ressourcesParCours as $coursId => $ressourcesCours)
                            @php
                                $cours = $ressourcesCours->first()->cours;
                            @endphp
                            @if($cours)
                                <option value="{{ $coursId }}">{{ $cours->nom }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Filtrer par type</label>
                    <select class="form-select" id="filterType">
                        <option value="">Tous les types</option>
                        @foreach($ressourcesParType as $type => $ressourcesType)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des ressources -->
    <div class="row g-4" id="ressourcesList">
        @foreach($ressources as $ressource)
            <div class="col-md-6 col-lg-4 ressource-item" 
                 data-cours="{{ $ressource->cours_id ?? '' }}"
                 data-type="{{ $ressource->type }}">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="card-title mb-0">{{ $ressource->titre }}</h6>
                            <span class="badge bg-secondary">{{ $ressource->type }}</span>
                        </div>
                        @if($ressource->cours)
                            <p class="text-muted small mb-2">Cours: {{ $ressource->cours->nom }}</p>
                        @endif
                        @if($ressource->enseignant)
                            <p class="text-muted small mb-3">Par: {{ $ressource->enseignant->name }}</p>
                        @endif
                        <a href="{{ route('etudiant.ressources.show', $ressource->id) }}" class="btn btn-sm btn-primary">
                            Voir la ressource
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        document.getElementById('filterCours').addEventListener('change', function() {
            filterRessources();
        });
        document.getElementById('filterType').addEventListener('change', function() {
            filterRessources();
        });

        function filterRessources() {
            const coursFilter = document.getElementById('filterCours').value;
            const typeFilter = document.getElementById('filterType').value;
            const items = document.querySelectorAll('.ressource-item');

            items.forEach(item => {
                const cours = item.getAttribute('data-cours');
                const type = item.getAttribute('data-type');
                
                let show = true;
                if (coursFilter && cours !== coursFilter) show = false;
                if (typeFilter && type !== typeFilter) show = false;
                
                item.style.display = show ? 'block' : 'none';
            });
        }
    </script>
@else
    <div class="alert alert-info">
        Aucune ressource disponible pour le moment.
    </div>
@endif
@endsection


