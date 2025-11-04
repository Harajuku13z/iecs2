@extends('layouts.admin')

@section('title', 'Candidature #' . $candidature->id)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Candidature #{{ $candidature->id }}</h1>
    <a href="{{ route('admin.candidatures.index') }}" class="btn btn-secondary">← Retour</a>
}</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">Étapes</h5>
                @php $statut = $candidature->statut; @endphp
                <ol class="list-group list-group-numbered">
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold">Inscription en Ligne</div>
                            Créez votre compte et soumettez votre dossier de candidature en quelques clics.
                        </div>
                        <span class="badge {{ $statut !== 'soumis' ? 'bg-success' : 'bg-secondary' }} rounded-pill">{{ $statut !== 'soumis' ? 'Validé' : 'En attente' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold">Vérification Administrative</div>
                            Notre équipe examine votre dossier sous 48h.
                        </div>
                        <form action="{{ route('admin.candidatures.updateStatus', $candidature) }}" method="POST" class="ms-3">
                            @csrf @method('PATCH')
                            <input type="hidden" name="statut" value="verifie">
                            <button class="btn btn-sm btn-outline-success" @disabled($statut==='verifie' || $statut==='admis')>Valider</button>
                        </form>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold">Évaluation du Comité</div>
                            <div class="small text-muted">Définir une date d'évaluation</div>
                            <form action="{{ route('admin.candidatures.schedule', $candidature) }}" method="POST" class="mt-2 d-flex gap-2 align-items-center">
                                @csrf @method('PATCH')
                                <input type="datetime-local" name="evaluation_date" value="{{ $candidature->evaluation_date ? \Carbon\Carbon::parse($candidature->evaluation_date)->format('Y-m-d\TH:i') : '' }}" class="form-control" style="max-width: 280px;">
                                <button class="btn btn-sm btn-outline-primary">Planifier</button>
                            </form>
                            @if($candidature->evaluation_date)
                                <div class="mt-2 small">Prévu le: <strong>{{ \Carbon\Carbon::parse($candidature->evaluation_date)->format('d/m/Y H:i') }}</strong></div>
                            @endif
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold">Décision d'Admission</div>
                            Recevez votre décision par email.
                        </div>
                        <div class="d-flex gap-2">
                            <form action="{{ route('admin.candidatures.updateStatus', $candidature) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="statut" value="admis">
                                <button class="btn btn-sm btn-outline-success" @disabled($statut==='admis')>Admettre</button>
                            </form>
                            <form action="{{ route('admin.candidatures.updateStatus', $candidature) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="statut" value="rejete">
                                <button class="btn btn-sm btn-outline-danger" @disabled($statut==='rejete')>Rejeter</button>
                            </form>
                        </div>
                    </li>
                </ol>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h5 class="mb-3">Candidat</h5>
                <div class="mb-1"><strong>{{ $candidature->user->name }}</strong></div>
                <div class="text-muted small">{{ $candidature->user->email }}</div>
            </div>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">Documents</h5>
                @php $docs = $candidature->documents ? json_decode($candidature->documents, true) : []; @endphp
                @if($docs)
                    <ul class="list-unstyled mb-0">
                        @foreach($docs as $doc)
                            <li class="mb-2">
                                <a href="{{ asset('storage/' . $doc['path']) }}" target="_blank" class="text-decoration-none">{{ $doc['label'] ?? $doc['name'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-muted">Aucun document</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection


