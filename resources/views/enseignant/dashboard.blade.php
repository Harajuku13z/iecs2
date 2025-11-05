@extends('layouts.enseignant')

@section('teacher_content')
<div class="mb-4">
    <h2 class="mb-2" style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-weight: 700;">
        Bienvenue, {{ Auth::user()->name }} 👋
    </h2>
    <p class="text-muted mb-0">Gérez vos cours, vos classes et vos étudiants en toute simplicité.</p>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Statistiques -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="teacher-stat-card">
            <div class="card-body text-center p-4">
                <h5 class="text-muted mb-2">📚 Mes Cours</h5>
                <h2 class="teacher-stat-number mb-0">{{ $cours->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="teacher-stat-card">
            <div class="card-body text-center p-4">
                <h5 class="text-muted mb-2">🏫 Mes Classes</h5>
                <h2 class="teacher-stat-number mb-0">{{ $classes->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="teacher-stat-card">
            <div class="card-body text-center p-4">
                <h5 class="text-muted mb-2">👥 Étudiants</h5>
                <h2 class="teacher-stat-number mb-0">{{ $totalEtudiants }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="teacher-stat-card">
            <div class="card-body text-center p-4">
                <h5 class="text-muted mb-2">📁 Ressources</h5>
                <h2 class="teacher-stat-number mb-0">{{ $ressources->count() }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Actions rapides -->
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="teacher-card">
            <div class="teacher-card-header">
                <h5 class="mb-0">⚡ Actions Rapides</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('enseignant.notes.create') }}" class="btn w-100 mb-2" style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); color: white; border: none; font-weight: 600;">
                        📝 Saisir des notes
                    </a>
                    <a href="{{ route('enseignant.examens.create') }}" class="btn w-100 mb-2" style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); color: white; border: none; font-weight: 600;">
                        📝 Créer un examen
                    </a>
                    <a href="{{ route('enseignant.ressources.create') }}" class="btn w-100" style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); color: white; border: none; font-weight: 600;">
                        📚 Partager une ressource
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="teacher-card">
            <div class="teacher-card-header">
                <h5 class="mb-0">📖 Mes Cours</h5>
            </div>
            <div class="card-body">
                @if($cours->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($cours->take(5) as $c)
                            <a href="{{ route('enseignant.cours.show', $c->id) }}" class="list-group-item list-group-item-action">
                                <strong>{{ $c->nom }}</strong> ({{ $c->code }})
                                <br>
                                <small class="text-muted">Coef: {{ $c->coefficient }}</small>
                            </a>
                        @endforeach
                    </div>
                    @if($cours->count() > 5)
                        <div class="mt-3">
                            <a href="{{ route('enseignant.cours.index') }}" class="btn btn-sm btn-outline-primary">
                                Voir tous les cours
                            </a>
                        </div>
                    @endif
                @else
                    <p class="text-muted mb-0">Aucun cours assigné pour le moment.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Dernières notes -->
@if($notes->count() > 0)
<div class="teacher-card mb-4">
    <div class="teacher-card-header">
        <h5 class="mb-0">📊 Dernières Notes Saisies</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Étudiant</th>
                        <th>Cours</th>
                        <th>Note</th>
                        <th>Type</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($notes as $note)
                        <tr>
                            <td>{{ $note->etudiant->name ?? 'N/A' }}</td>
                            <td>{{ $note->cours->nom ?? 'N/A' }}</td>
                            <td><strong>{{ number_format($note->note, 2) }}/20</strong></td>
                            <td>{{ $note->type_evaluation ?? 'N/A' }}</td>
                            <td>{{ $note->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<!-- Calendrier d'intervention -->
@if($calendrier->count() > 0)
<div class="teacher-card mb-4">
    <div class="teacher-card-header">
        <h5 class="mb-0">📅 Mon Calendrier d'Intervention</h5>
    </div>
    <div class="card-body p-4">
        @foreach($calendrierParSemestre as $semestre => $coursSemestre)
            <div class="mb-5">
                <div class="d-flex align-items-center mb-4">
                    <div class="badge" style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); color: white; padding: 0.5rem 1rem; font-size: 1rem; font-weight: 600;">
                        {{ $semestre ?? 'Non spécifié' }}
                    </div>
                    <span class="ms-3 text-muted">{{ $coursSemestre->count() }} cours</span>
                </div>
                
                @php
                    $joursOrder = ['Lundi' => 1, 'Mardi' => 2, 'Mercredi' => 3, 'Jeudi' => 4, 'Vendredi' => 5, 'Samedi' => 6, 'Dimanche' => 7];
                    $coursParJour = $coursSemestre->groupBy('jour_semaine')->sortBy(function($items, $key) use ($joursOrder) {
                        return $joursOrder[$key] ?? 999;
                    });
                @endphp
                
                @foreach($coursParJour as $jour => $coursJour)
                    <div class="calendrier-jour mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="jour-badge" style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); color: white; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 700; font-size: 1.1rem; min-width: 150px; text-align: center;">
                                {{ $jour }}
                            </div>
                            <div class="ms-3">
                                <span class="badge bg-light text-dark">{{ $coursJour->count() }} cours</span>
                            </div>
                        </div>
                        
                        <div class="row g-3">
                            @foreach($coursJour->sortBy('heure_debut') as $cal)
                                <div class="col-md-6 col-lg-4">
                                    <div class="calendrier-cours-card" style="background: white; border: 2px solid var(--color-primary); border-radius: 12px; padding: 1.25rem; transition: all 0.3s ease; height: 100%;">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div class="heure-badge" style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); color: white; padding: 0.4rem 0.8rem; border-radius: 6px; font-weight: 600; font-size: 0.85rem;">
                                                {{ date('H:i', strtotime($cal->heure_debut)) }} - {{ date('H:i', strtotime($cal->heure_fin)) }}
                                            </div>
                                        </div>
                                        
                                        <h6 class="mb-2" style="color: var(--color-primary); font-weight: 700; font-size: 1rem;">
                                            {{ $cal->cours->nom ?? 'Cours non spécifié' }}
                                        </h6>
                                        
                                        @if($cal->cours)
                                            <p class="mb-2" style="color: #6c757d; font-size: 0.85rem;">
                                                <strong>Code:</strong> {{ $cal->cours->code }}
                                            </p>
                                        @endif
                                        
                                        <div class="mb-2">
                                            <span class="badge bg-light text-dark" style="font-size: 0.8rem;">
                                                🏫 {{ $cal->classe->nom ?? 'Classe non spécifiée' }}
                                            </span>
                                        </div>
                                        
                                        @if($cal->classe && $cal->classe->filiere)
                                            <p class="mb-2" style="color: #6c757d; font-size: 0.8rem; margin: 0;">
                                                <small>{{ $cal->classe->filiere->nom }}</small>
                                            </p>
                                        @endif
                                        
                                        @if($cal->salle)
                                            <div class="mt-2 pt-2" style="border-top: 1px solid #e9ecef;">
                                                <span class="text-muted" style="font-size: 0.85rem;">
                                                    📍 <strong>Salle:</strong> {{ $cal->salle }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            
            @if(!$loop->last)
                <hr style="border-color: rgba(166, 96, 96, 0.2); margin: 2rem 0;">
            @endif
        @endforeach
    </div>
</div>
@else
<div class="teacher-card mb-4">
    <div class="card-body text-center text-muted p-4">
        <p class="mb-0">Aucun calendrier d'intervention disponible pour le moment.</p>
    </div>
</div>
@endif

<style>
    .calendrier-cours-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(166, 96, 96, 0.25);
        border-color: var(--color-secondary) !important;
    }
    
    .calendrier-jour:not(:last-child) {
        padding-bottom: 2rem;
        border-bottom: 2px dashed rgba(166, 96, 96, 0.2);
    }
</style>

<!-- Ressources récentes -->
@if($ressources->count() > 0)
<div class="teacher-card">
    <div class="teacher-card-header">
        <h5 class="mb-0">📁 Ressources Récentes</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Type</th>
                        <th>Cours</th>
                        <th>Classe</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ressources as $ressource)
                        <tr>
                            <td>{{ $ressource->titre }}</td>
                            <td><span class="badge bg-secondary">{{ $ressource->type }}</span></td>
                            <td>{{ $ressource->cours->nom ?? 'N/A' }}</td>
                            <td>{{ $ressource->classe->nom ?? 'N/A' }}</td>
                            <td>{{ $ressource->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection
