@extends('layouts.admin')

@section('title', 'Tableau de Bord Admin')

@section('content')
<h1 class="mb-4">Tableau de Bord</h1>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">👨‍🎓 Étudiants</h5>
                <h2 class="mb-0">{{ $stats['total_etudiants'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">👨‍🏫 Enseignants</h5>
                <h2 class="mb-0">{{ $stats['total_enseignants'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title">📝 Candidats</h5>
                <h2 class="mb-0">{{ $stats['total_candidats'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title">📚 Filières</h5>
                <h2 class="mb-0">{{ $stats['total_filieres'] }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">🏫 Classes</h5>
                <h2 class="mb-0">{{ $stats['total_classes'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">📖 Cours</h5>
                <h2 class="mb-0">{{ $stats['total_cours'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-warning">
            <div class="card-body">
                <h5 class="card-title">⏳ Candidatures en attente</h5>
                <h2 class="mb-0">{{ $stats['candidatures_en_attente'] }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">Candidatures Récentes</h5>
    </div>
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recent_candidatures as $candidature)
                    <tr>
                        <td>{{ $candidature->user->name }}</td>
                        <td>{{ $candidature->user->email }}</td>
                        <td>
                            @if($candidature->statut === 'soumis')
                                <span class="badge bg-warning">Soumis</span>
                            @elseif($candidature->statut === 'verifie')
                                <span class="badge bg-info">Vérifié</span>
                            @elseif($candidature->statut === 'admis')
                                <span class="badge bg-success">Admis</span>
                            @else
                                <span class="badge bg-danger">Rejeté</span>
                            @endif
                        </td>
                        <td>{{ $candidature->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('admin.candidatures.index') }}" class="btn btn-sm btn-primary">
                                Gérer
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Aucune candidature récente</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

