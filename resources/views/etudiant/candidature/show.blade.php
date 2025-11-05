@extends('layouts.student')

@section('student_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>📋 Ma Candidature</h3>
    @if(!$candidature)
        <a href="{{ route('candidature.edit') }}" class="btn btn-primary">
            Créer ma candidature
        </a>
    @endif
</div>

@if($message ?? false)
    <div class="alert alert-info">
        {{ $message }}
    </div>
@elseif($candidature)
    <style>
        .status-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        .status-soumis { background: #ffc107; color: #000; }
        .status-verifie { background: #17a2b8; color: white; }
        .status-evalue { background: #6c757d; color: white; }
        .status-admis { background: #28a745; color: white; }
        .status-rejete { background: #dc3545; color: white; }
    </style>

    <!-- Statut -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Statut de la Candidature</h5>
        </div>
        <div class="card-body">
            <div class="text-center mb-4">
                <span class="status-badge status-{{ $candidature->statut }}">
                    {{ ucfirst($candidature->statut) }}
                </span>
            </div>
            
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

    <!-- Informations de la candidature -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Informations de la Candidature</h5>
        </div>
        <div class="card-body">
            <div class="row">
                @if($candidature->filiere)
                    <div class="col-md-6 mb-3">
                        <strong>Filière demandée:</strong>
                        <p>{{ $candidature->filiere->nom }}</p>
                    </div>
                @endif
                @if($candidature->specialite)
                    <div class="col-md-6 mb-3">
                        <strong>Spécialité:</strong>
                        <p>{{ $candidature->specialite->nom }}</p>
                    </div>
                @endif
                @if($candidature->classe)
                    <div class="col-md-6 mb-3">
                        <strong>Classe assignée:</strong>
                        <p>{{ $candidature->classe->nom }} - {{ optional($candidature->classe->filiere)->nom }}</p>
                    </div>
                @endif
                <div class="col-md-6 mb-3">
                    <strong>Date de soumission:</strong>
                    <p>{{ $candidature->created_at->format('d/m/Y à H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Commentaires admin -->
    @if($candidature->commentaire_admin)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Commentaire de l'Administration</h5>
            </div>
            <div class="card-body">
                <p>{{ $candidature->commentaire_admin }}</p>
            </div>
        </div>
    @endif

    <!-- Historique des validations -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Historique des Validations</h5>
        </div>
        <div class="card-body">
            <ul class="list-unstyled">
                @if($candidature->verified_by)
                    <li class="mb-2">
                        <strong>✅ Vérification:</strong> 
                        {{ \App\Models\User::find($candidature->verified_by)->name ?? 'N/A' }}
                        @if($candidature->evaluation_date)
                            - {{ \Carbon\Carbon::parse($candidature->evaluation_date)->format('d/m/Y') }}
                        @endif
                    </li>
                @endif
                @if($candidature->evaluated_by)
                    <li class="mb-2">
                        <strong>📊 Évaluation:</strong> 
                        {{ \App\Models\User::find($candidature->evaluated_by)->name ?? 'N/A' }}
                    </li>
                @endif
                @if($candidature->decided_by)
                    <li class="mb-2">
                        <strong>⚖️ Décision:</strong> 
                        {{ \App\Models\User::find($candidature->decided_by)->name ?? 'N/A' }}
                    </li>
                @endif
            </ul>
        </div>
    </div>

    <!-- Statut des Documents -->
    <div class="card border-0 shadow-sm mt-3">
        <div class="card-header bg-white">
            <h5 class="mb-0">Statut des Documents</h5>
        </div>
        <div class="card-body">
            @php
                $docsRaw = $candidature->documents;
                $docs = is_array($docsRaw) ? $docsRaw : ($docsRaw ? json_decode($docsRaw, true) : []);
                $statuses = $candidature->document_statuses ?: [];
                $byKey = collect($docs)->keyBy('key');
                $required = [
                    'doc_identite' => "Pièce d'identité",
                    'doc_diplome' => 'Diplôme',
                    'doc_releve' => 'Relevé de notes',
                    'doc_lettre' => 'Lettre de motivation',
                    'doc_photo' => 'Photo',
                ];
            @endphp
            <div class="list-group">
                @foreach($required as $key => $label)
                    @php $d = $byKey->get($key); $st = $statuses[$key] ?? null; @endphp
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold">{{ $label }}</div>
                            @if($d)
                                <div class="small text-muted">{{ $d['name'] ?? basename($d['path']) }}</div>
                            @endif
                            @if($key==='doc_photo' && ($candidature->user->profile_photo ?? null))
                                <span class="badge bg-success mt-1">Conforme (photo de profil)</span>
                            @elseif($st)
                                <span class="badge {{ $st['status']==='conforme' ? 'bg-success' : 'bg-danger' }} mt-1">{{ ucfirst(str_replace('_',' ', $st['status'])) }}</span>
                                @if(!empty($st['note']))<span class="small ms-2">{{ $st['note'] }}</span>@endif
                            @else
                                <span class="badge bg-secondary mt-1">À valider</span>
                            @endif
                        </div>
                        <div class="d-flex gap-2">
                            @if($d)
                                <a class="btn btn-sm btn-outline-primary" target="_blank" href="{{ asset('storage/' . $d['path']) }}">Voir</a>
                            @endif
                            @if(!$d || ($st && $st['status']==='non_conforme'))
                                <a class="btn btn-sm btn-outline-warning" href="{{ route('candidature.edit') }}">Remplacer</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
@endsection



