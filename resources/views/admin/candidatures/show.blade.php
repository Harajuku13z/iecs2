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
                @php
                    $docs = $candidature->documents ? json_decode($candidature->documents, true) : [];
                    $photo = collect($docs)->firstWhere('key', 'doc_photo');
                @endphp
                @if($photo)
                    <img src="{{ asset('storage/' . $photo['path']) }}" alt="Photo" class="mb-2" style="width:80px; height:80px; object-fit:cover; border-radius:50%;">
                @endif
                <div class="mb-1"><strong>{{ $candidature->user->name }}</strong></div>
                <div class="text-muted small">{{ $candidature->user->email }}</div>
            </div>
        </div>
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h5 class="mb-3">Documents</h5>
                @php
                    $required = [
                        'doc_identite' => "Pièce d'identité",
                        'doc_diplome' => 'Diplôme',
                        'doc_releve' => 'Relevé de notes',
                        'doc_lettre' => 'Lettre de motivation',
                        'doc_photo' => 'Photo',
                    ];
                    $presentKeys = collect($docs)->pluck('key')->filter()->values()->all();
                @endphp
                <ul class="list-unstyled mb-3">
                    @foreach($required as $key => $label)
                        <li class="d-flex justify-content-between align-items-center mb-2">
                            <span>{{ $label }}</span>
                            @php $found = collect($docs)->firstWhere('key', $key); @endphp
                            @if($found)
                                <a href="{{ asset('storage/' . $found['path']) }}" target="_blank" class="badge bg-success text-decoration-none">✔</a>
                            @else
                                <span class="badge bg-secondary">—</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
                @php
                    $missing = collect($required)->reject(function ($label, $key) use ($presentKeys) {
                        return in_array($key, $presentKeys, true);
                    })->toArray();
                @endphp
                @if(count($missing))
                    <form action="{{ route('admin.candidatures.remind', $candidature) }}" method="POST">
                        @csrf @method('PATCH')
                        <button class="btn btn-sm btn-outline-warning w-100">Rappeler le candidat (pièces manquantes)</button>
                    </form>
                @endif
            </div>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">Associer à une classe</h5>
                <form action="{{ route('admin.candidatures.assignClass', $candidature) }}" method="POST" class="d-flex gap-2">
                    @csrf @method('PATCH')
                    <select name="classe_id" class="form-select">
                        @foreach($classes as $classe)
                            <option value="{{ $classe->id }}" @selected($candidature->user->classe_id==$classe->id)>{{ $classe->nom }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-outline-primary">Associer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


