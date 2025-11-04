@extends('layouts.student')

@section('student_content')
@php
    $user = Auth::user();
    $classe = $user->classe ?? null;
    $cours = method_exists($user, 'cours') ? $user->cours()->with('classes')->get() : collect();
    $notes = method_exists($user, 'notes') ? $user->notes()->with('cours')->latest()->take(5)->get() : collect();
    $ressources = \App\Models\Ressource::query()
        ->when($classe, fn($q) => $q->where('classe_id', $classe->id))
        ->latest()->take(5)->get();
    $events = \App\Models\Evenement::where('publie', true)->orderBy('date_debut')->take(5)->get();
@endphp

<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h3 class="mb-1">Bienvenue, {{ $user->name }} 👋</h3>
        <small class="text-muted">@if($classe) Classe: {{ $classe->nom }} @else Classe non définie @endif</small>
    </div>
    <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-primary">Modifier mon profil</a>
    </div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted">Mes Cours</div>
                        <div class="h4 mb-0">{{ $cours->count() }}</div>
                    </div>
                    <div>📘</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted">Ressources</div>
                        <div class="h4 mb-0">{{ $ressources->count() }}</div>
                    </div>
                    <div>📂</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted">Événements</div>
                        <div class="h4 mb-0">{{ $events->count() }}</div>
                    </div>
                    <div>📅</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white">
                <strong>Mes cours</strong>
            </div>
            <div class="card-body">
                @if($cours->isEmpty())
                    <p class="text-muted mb-0">Aucun cours pour le moment.</p>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($cours as $c)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $c->nom }} <small class="text-muted">({{ $c->code }})</small></span>
                                <span class="badge bg-light text-dark">Coef {{ $c->coefficient }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white">
                <strong>Dernières notes</strong>
            </div>
            <div class="card-body">
                @if($notes->isEmpty())
                    <p class="text-muted mb-0">Aucune note enregistrée.</p>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($notes as $n)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $n->cours->nom ?? 'Cours' }} <small class="text-muted">({{ $n->type_evaluation }})</small></span>
                                <span class="badge bg-success">{{ number_format($n->note, 2) }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white">
                <strong>Ressources récentes</strong>
            </div>
            <div class="card-body">
                @if($ressources->isEmpty())
                    <p class="text-muted mb-0">Aucune ressource disponible.</p>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($ressources as $r)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $r->titre }} <small class="text-muted">({{ $r->type }})</small></span>
                                <a class="btn btn-sm btn-outline-secondary" href="#">Ouvrir</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white">
                <strong>Événements à venir</strong>
            </div>
            <div class="card-body">
                @if($events->isEmpty())
                    <p class="text-muted mb-0">Aucun événement à venir.</p>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($events as $e)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $e->titre }} <small class="text-muted">{{ \Carbon\Carbon::parse($e->date_debut)->format('d/m/Y H:i') }}</small></span>
                                <a class="btn btn-sm btn-outline-primary" href="#">Détails</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Tableau de Bord Étudiant')

@section('content')
<div class="container py-5">
    @if(auth()->user()->isCandidat())
        <h1 class="mb-4">Suivi de Candidature</h1>
        
        @if(auth()->user()->candidature)
            <div class="card">
                <div class="card-body">
                    <h5>Statut de votre candidature</h5>
                    <div class="alert alert-{{ auth()->user()->candidature->statut === 'admis' ? 'success' : (auth()->user()->candidature->statut === 'rejete' ? 'danger' : 'info') }}">
                        <strong>Statut actuel:</strong> {{ ucfirst(auth()->user()->candidature->statut) }}
                    </div>
                    
                    @if(auth()->user()->candidature->commentaire_admin)
                        <div class="mt-3">
                            <strong>Commentaire de l'administration:</strong>
                            <p>{{ auth()->user()->candidature->commentaire_admin }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="alert alert-warning">
                Vous n'avez pas encore soumis de candidature.
                <a href="{{ route('admission') }}" class="btn btn-primary btn-sm">Soumettre maintenant</a>
            </div>
        @endif
    @else
        <h1 class="mb-4">Tableau de Bord Étudiant</h1>
        
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">📊 Mes Notes</h5>
                    </div>
                    <div class="card-body">
                        @if(auth()->user()->notes->count() > 0)
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Cours</th>
                                        <th>Note</th>
                                        <th>Coef</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(auth()->user()->notes as $note)
                                        <tr>
                                            <td>{{ $note->cours->nom }}</td>
                                            <td>{{ $note->note }}/20</td>
                                            <td>{{ $note->cours->coefficient }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-muted">Aucune note disponible pour le moment.</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">📚 Ressources Pédagogiques</h5>
                    </div>
                    <div class="card-body">
                        @if(auth()->user()->classe)
                            @php
                                $ressources = \App\Models\Ressource::where('classe_id', auth()->user()->classe_id)->get();
                            @endphp
                            @if($ressources->count() > 0)
                                <ul class="list-group">
                                    @foreach($ressources as $ressource)
                                        <li class="list-group-item">
                                            {{ $ressource->titre }} ({{ $ressource->type }})
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted">Aucune ressource disponible.</p>
                            @endif
                        @else
                            <p class="text-muted">Vous n'êtes pas encore affecté à une classe.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        @if(auth()->user()->classe)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">ℹ️ Informations de Classe</h5>
                </div>
                <div class="card-body">
                    <p><strong>Classe:</strong> {{ auth()->user()->classe->nom }}</p>
                    <p><strong>Filière:</strong> {{ auth()->user()->classe->filiere->nom }}</p>
                    <p><strong>Niveau:</strong> {{ auth()->user()->classe->niveau->nom }}</p>
                </div>
            </div>
        @endif
    @endif
</div>
@endsection

