@extends('layouts.app')

@section('title', 'Tableau de Bord Enseignant')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Tableau de Bord Enseignant</h1>
    
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">📖 Mes Cours</h5>
                </div>
                <div class="card-body">
                    @if(auth()->user()->cours->count() > 0)
                        <ul class="list-group">
                            @foreach(auth()->user()->cours as $cours)
                                <li class="list-group-item">
                                    <strong>{{ $cours->nom }}</strong> ({{ $cours->code }})
                                    <br>
                                    <small class="text-muted">Coefficient: {{ $cours->coefficient }}</small>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">Aucun cours assigné pour le moment.</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">📊 Actions Rapides</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" disabled>📝 Saisir des notes</button>
                        <button class="btn btn-info" disabled>📤 Importer des notes (CSV)</button>
                        <button class="btn btn-warning" disabled>📚 Partager des ressources</button>
                    </div>
                    <p class="text-muted mt-3 small">Ces fonctionnalités seront bientôt disponibles</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">📋 Ressources Partagées</h5>
        </div>
        <div class="card-body">
            @if(auth()->user()->ressources->count() > 0)
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
                        @foreach(auth()->user()->ressources as $ressource)
                            <tr>
                                <td>{{ $ressource->titre }}</td>
                                <td><span class="badge bg-info">{{ $ressource->type }}</span></td>
                                <td>{{ $ressource->cours->nom ?? 'N/A' }}</td>
                                <td>{{ $ressource->classe->nom ?? 'N/A' }}</td>
                                <td>{{ $ressource->created_at->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-muted">Vous n'avez pas encore partagé de ressources.</p>
            @endif
        </div>
    </div>
</div>
@endsection

