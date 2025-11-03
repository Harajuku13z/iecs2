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

