@extends('layouts.student')

@section('student_content')
<style>
.status-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.9rem;
}

.status-soumis {
    background: #ffc107;
    color: #000;
}

.status-verifie {
    background: #17a2b8;
    color: white;
}

.status-evalue {
    background: #6c757d;
    color: white;
}

.status-admis {
    background: #28a745;
    color: white;
}

.status-rejete {
    background: #dc3545;
    color: white;
}
</style>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h3 class="mb-1">Bienvenue, {{ $user->name }} 👋</h3>
        <small class="text-muted">Espace Étudiant - Suivi de Candidature</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-primary">Modifier mon profil</a>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($candidature)
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">📋 Suivi de Candidature</h5>
        </div>
        <div class="card-body">
            <div class="mb-4">
                <h5>Statut actuel</h5>
                <span class="status-badge status-{{ $candidature->statut }}">
                    {{ ucfirst($candidature->statut) }}
                </span>
            </div>
            
            @if($candidature->filiere)
                <div class="mb-3">
                    <strong>Filière demandée:</strong> {{ $candidature->filiere->nom }}
                </div>
            @endif
            
            @if($candidature->specialite)
                <div class="mb-3">
                    <strong>Spécialité:</strong> {{ $candidature->specialite->nom }}
                </div>
            @endif
            
            @if($candidature->commentaire_admin)
                <div class="alert alert-info">
                    <strong>Commentaire de l'administration:</strong>
                    <p class="mb-0">{{ $candidature->commentaire_admin }}</p>
                </div>
            @endif
            
            @if($candidature->statut === 'admis')
                <div class="alert alert-success">
                    <strong>✅ Félicitations !</strong> Votre candidature a été acceptée. Vous recevrez bientôt plus d'informations.
                </div>
            @elseif($candidature->statut === 'rejete')
                <div class="alert alert-danger">
                    <strong>❌ Désolé</strong> Votre candidature n'a pas été retenue pour cette session.
                </div>
            @else
                <div class="alert alert-warning">
                    <strong>⏳ En attente</strong> Votre candidature est en cours d'examen. Vous serez notifié dès qu'une décision sera prise.
                </div>
            @endif
        </div>
    </div>
@else
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <h4 class="mb-3">Vous n'avez pas encore soumis de candidature</h4>
            <p class="text-muted mb-4">Commencez votre candidature maintenant pour rejoindre l'IESCA.</p>
            <a href="{{ route('candidature.create') }}" class="btn" style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); color: white; border-radius: 8px; padding: 0.75rem 2rem;">
                Soumettre ma candidature
            </a>
        </div>
    </div>
@endif
@endsection

