@extends('layouts.admin')

@section('title', 'Candidature #' . $candidature->id)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Candidature #{{ $candidature->id }}</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.candidatures.edit', $candidature) }}" class="btn btn-primary">Modifier</a>
        <form action="{{ route('admin.candidatures.destroy', $candidature) }}" method="POST" onsubmit="return confirm('Supprimer définitivement cette candidature ?');">
            @csrf @method('DELETE')
            <button class="btn btn-danger">Supprimer</button>
        </form>
        <a href="{{ route('register') }}" target="_blank" class="btn btn-outline-primary">Créer un compte</a>
        <a href="{{ route('admin.candidatures.index') }}" class="btn btn-secondary">← Retour</a>
    </div>
}</div>

<div class="row g-4" style="background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%); padding: 1rem; border-radius: 8px;">
    <div class="col-lg-4">
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
                        <span class="badge {{ in_array($statut, ['verifie', 'admis']) ? 'bg-success' : ($statut === 'rejete' ? 'bg-danger' : 'bg-secondary') }} rounded-pill">
                            @if($statut === 'verifie' || $statut === 'admis')
                                Validé
                            @elseif($statut === 'rejete')
                                Rejeté
                            @else
                                En attente
                            @endif
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold">Vérification Administrative</div>
                            Notre équipe examine votre dossier sous 48h.
                            @if($candidature->verified_by)
                                <div class="small text-muted mt-1">Validé par: <strong>{{ optional(\App\Models\User::find($candidature->verified_by))->name }}</strong></div>
                            @endif
                        </div>
                        <form action="{{ route('admin.candidatures.updateStatus', $candidature) }}" method="POST" class="ms-3">
                            @csrf @method('PATCH')
                            <input type="hidden" name="statut" value="verifie">
                            <button class="btn btn-sm btn-success" @disabled($statut==='verifie' || $statut==='admis')>Valider</button>
                        </form>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold">Évaluation du Comité</div>
                            <div class="small text-muted">Définir une date d'évaluation</div>
                            <form action="{{ route('admin.candidatures.schedule', $candidature) }}" method="POST" class="mt-2 d-flex gap-2 align-items-center">
                                @csrf @method('PATCH')
                                <input type="datetime-local" name="evaluation_date" value="{{ $candidature->evaluation_date ? \Carbon\Carbon::parse($candidature->evaluation_date)->format('Y-m-d\TH:i') : '' }}" class="form-control" style="max-width: 280px;">
                                <button class="btn btn-sm btn-primary">Planifier</button>
                            </form>
                            @if($candidature->evaluation_date)
                                <div class="mt-2 small">Prévu le: <strong>{{ \Carbon\Carbon::parse($candidature->evaluation_date)->format('d/m/Y H:i') }}</strong></div>
                            @endif
                            <div class="mt-2 d-flex gap-2">
                                <form action="{{ route('admin.candidatures.markEvaluated', $candidature) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm btn-success" @disabled(!$candidature->evaluation_date || $candidature->evaluated_by)>Valider l'évaluation</button>
                                </form>
                                @if($candidature->evaluated_by)
                                    <div class="small text-muted">Évalué par: <strong>{{ optional(\App\Models\User::find($candidature->evaluated_by))->name }}</strong></div>
                                @endif
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold">Décision d'Admission</div>
                            Recevez votre décision par email.
                            @if($candidature->decided_by)
                                <div class="small text-muted mt-1">Décidé par: <strong>{{ optional(\App\Models\User::find($candidature->decided_by))->name }}</strong></div>
                            @endif
                        </div>
                        <div class="d-flex gap-2">
                            <form action="{{ route('admin.candidatures.updateStatus', $candidature) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="statut" value="admis">
                                <button class="btn btn-sm btn-success" @disabled($statut==='admis')>Admettre</button>
                            </form>
                            <form action="{{ route('admin.candidatures.updateStatus', $candidature) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="statut" value="rejete">
                                <button class="btn btn-sm btn-danger" @disabled($statut==='rejete')>Rejeter</button>
                            </form>
                        </div>
                    </li>
                </ol>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h5 class="mb-3">Informations du Candidat</h5>
                <div class="text-center mb-3">
                    @php
                        $docsRaw = $candidature->documents;
                        $docs = is_array($docsRaw) ? $docsRaw : ($docsRaw ? json_decode($docsRaw, true) : []);
                        $photo = collect($docs)->firstWhere('key', 'doc_photo');
                        $profilePhoto = $candidature->user->profile_photo ?? null;
                    @endphp
                    @if($profilePhoto)
                        <img src="{{ asset('storage/' . $profilePhoto) }}" alt="Photo de profil" class="mb-2" style="width:150px; height:150px; object-fit:cover; border-radius:50%; border: 3px solid var(--color-primary);" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-flex';">
                        <div class="mb-2" style="width:150px; height:150px; border-radius:50%; background: #ddd; display: none; align-items: center; justify-content: center; font-size: 3rem; margin: 0 auto;">👤</div>
                    @elseif($photo && isset($photo['path']))
                        <img src="{{ asset('storage/' . $photo['path']) }}" alt="Photo" class="mb-2" style="width:150px; height:150px; object-fit:cover; border-radius:50%; border: 3px solid var(--color-primary);" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-flex';">
                        <div class="mb-2" style="width:150px; height:150px; border-radius:50%; background: #ddd; display: none; align-items: center; justify-content: center; font-size: 3rem; margin: 0 auto;">👤</div>
                    @else
                        <div class="mb-2" style="width:150px; height:150px; border-radius:50%; background: #ddd; display: inline-flex; align-items: center; justify-content: center; font-size: 3rem; margin: 0 auto;">👤</div>
                    @endif
                </div>
                <div class="mb-2">
                    <strong style="font-size: 1.2rem;">{{ $candidature->user->name }}</strong>
                </div>
                <div class="text-muted mb-2">{{ $candidature->user->email }}</div>
                <div class="mb-2">
                    <strong>Téléphone:</strong> 
                    <span>{{ $candidature->user->phone ?? 'Non renseigné' }}</span>
                </div>
                @if($candidature->user->contact_name || $candidature->user->contact_phone)
                    <div class="mb-2">
                        <strong>Contact:</strong> 
                        {{ $candidature->user->contact_name ?? '' }} 
                        @if($candidature->user->contact_name && $candidature->user->contact_phone) - @endif
                        {{ $candidature->user->contact_phone ?? '' }}
                    </div>
                @endif
                @if($candidature->filiere)
                    <div class="mb-2"><strong>Filière:</strong> {{ $candidature->filiere->nom }}</div>
                @endif
                @if($candidature->specialite)
                    <div class="mb-2"><strong>Spécialité:</strong> {{ $candidature->specialite->nom }}</div>
                @endif
                @if($candidature->classe)
                    <div class="mb-2"><strong>Classe:</strong> {{ $candidature->classe->nom }}</div>
                @endif
                
                @php
                    $lettreDoc = collect($docs)->firstWhere('key', 'doc_lettre');
                @endphp
                @if($lettreDoc)
                    <div class="mt-3 pt-3 border-top">
                        <h6 class="mb-2">Lettre de motivation</h6>
                        @if(isset($lettreDoc['text']) && !empty($lettreDoc['text']))
                            <div class="mb-2 p-3 bg-light rounded" style="max-height: 300px; overflow-y: auto; font-size: 0.9rem; white-space: pre-wrap;">
                                {{ $lettreDoc['text'] }}
                            </div>
                        @endif
                        @if(isset($lettreDoc['path']))
                            <div class="mb-2">
                                <a href="{{ asset('storage/' . $lettreDoc['path']) }}" target="_blank" class="btn btn-sm btn-outline-primary">📄 Voir le PDF</a>
                                <a href="{{ asset('storage/' . $lettreDoc['path']) }}" download class="btn btn-sm btn-outline-secondary">⬇️ Télécharger</a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h5 class="mb-3">Frais d'inscription</h5>
                @if($candidature->inscription_paid)
                    <div class="alert alert-success mb-2">Payé le {{ optional($candidature->inscription_paid_at)->format('d/m/Y H:i') }} par <strong>{{ optional(\App\Models\User::find($candidature->inscription_paid_by))->name }}</strong></div>
                @else
                    <div class="alert alert-warning mb-2">Non réglé</div>
                    <form action="{{ route('admin.candidatures.markInscriptionPaid', $candidature) }}" method="POST">
                        @csrf @method('PATCH')
                        <button class="btn btn-sm btn-success">Marquer comme payé</button>
                    </form>
                @endif
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
                    $statuses = $candidature->document_statuses ?: [];
                @endphp
                <ul class="list-unstyled mb-3">
                    @foreach($required as $key => $label)
                        <li class="d-flex justify-content-between align-items-center mb-2">
                            <span>
                                {{ $label }}
                                @if($key !== 'doc_photo')
                                    <span class="badge bg-light text-muted" title="clé">{{ $key }}</span>
                                @endif
                            </span>
                            @php 
                                $found = collect($docs)->firstWhere('key', $key);
                                if($key==='doc_photo' && !$found && !empty($candidature->user->profile_photo)){
                                    $found = ['path' => $candidature->user->profile_photo, 'label' => 'Photo de profil'];
                                }
                            @endphp
                            @if($found)
                                <div class="d-flex gap-2 align-items-center">
                                    @php $st = $statuses[$key] ?? null; @endphp
                                    @if(($key==='doc_photo' && $candidature->user->profile_photo) || ($st && $st['status']==='conforme'))
                                        <span class="badge bg-success">Conforme</span>
                                    @elseif($st && $st['status']==='non_conforme')
                                        <span class="badge bg-danger">Non conforme</span>
                                    @else
                                        <span class="badge bg-secondary">À valider</span>
                                    @endif
                                    <a href="{{ asset('storage/' . $found['path']) }}" target="_blank" class="btn btn-sm btn-outline-primary">Voir</a>
                                    <a href="{{ asset('storage/' . $found['path']) }}" download class="btn btn-sm btn-outline-secondary">Télécharger</a>
                                    <form action="{{ route('admin.candidatures.updateDocStatus', $candidature) }}" method="POST" class="d-flex gap-2">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="key" value="{{ $key }}">
                                        <input type="text" name="note" class="form-control form-control-sm" placeholder="Note (optionnel)" style="max-width:200px;">
                                        <button name="status" value="conforme" class="btn btn-sm btn-success">Conforme</button>
                                        <button name="status" value="non_conforme" class="btn btn-sm btn-danger">Non conforme</button>
                                    </form>
                                </div>
                            @else
                                <span class="badge bg-secondary">—</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
                @php
                    $requiredKeys = array_keys($required);
                    $others = collect($docs)->filter(function($d) use ($requiredKeys){ return isset($d['key']) && !in_array($d['key'], $requiredKeys, true); })->values()->all();
                @endphp
                @if(count($others))
                    <h6 class="mb-2">Autres documents</h6>
                    <ul class="list-unstyled mb-3">
                        @foreach($others as $d)
                            <li class="d-flex justify-content-between align-items-center mb-2">
                                <span>{{ $d['label'] ?? 'Document' }} <span class="badge bg-light text-muted" title="clé">{{ $d['key'] ?? '—' }}</span></span>
                                <div class="d-flex gap-2 align-items-center">
                                    @php $k = $d['key'] ?? ''; $st = $statuses[$k] ?? null; @endphp
                                    @if($st && $st['status']==='conforme')
                                        <span class="badge bg-success">Conforme</span>
                                    @elseif($st && $st['status']==='non_conforme')
                                        <span class="badge bg-danger">Non conforme</span>
                                    @else
                                        <span class="badge bg-secondary">À valider</span>
                                    @endif
                                    @if(!empty($d['path']))
                                        <a href="{{ asset('storage/' . $d['path']) }}" target="_blank" class="btn btn-sm btn-outline-primary">Voir</a>
                                        <a href="{{ asset('storage/' . $d['path']) }}" download class="btn btn-sm btn-outline-secondary">Télécharger</a>
                                    @endif
                                    <form action="{{ route('admin.candidatures.updateDocStatus', $candidature) }}" method="POST" class="d-flex gap-2">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="key" value="{{ $k }}">
                                        <input type="text" name="note" class="form-control form-control-sm" placeholder="Note (optionnel)" style="max-width:200px;">
                                        <button name="status" value="conforme" class="btn btn-sm btn-success">Conforme</button>
                                        <button name="status" value="non_conforme" class="btn btn-sm btn-danger">Non conforme</button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
                @php
                    $missing = collect($required)->reject(function ($label, $key) use ($presentKeys) {
                        return in_array($key, $presentKeys, true);
                    })->toArray();
                @endphp
                <div class="d-flex flex-column gap-2">
                    @if(count($missing))
                        <form action="{{ route('admin.candidatures.remind', $candidature) }}" method="POST">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm btn-warning w-100">Rappeler le candidat (pièces manquantes)</button>
                        </form>
                    @endif
                    <button class="btn btn-sm btn-outline-dark w-100" onclick="window.print()">Imprimer la candidature</button>
                </div>
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


