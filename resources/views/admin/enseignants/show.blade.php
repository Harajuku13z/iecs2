@extends('layouts.admin')

@section('title', 'Détails Enseignant: ' . $enseignant->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>👨‍🏫 {{ $enseignant->name }}</h1>
    <a href="{{ route('admin.enseignants.index') }}" class="btn btn-secondary">← Retour</a>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Informations</h5>
                <p><strong>Email:</strong> {{ $enseignant->email }}</p>
                <p><strong>Téléphone:</strong> {{ $enseignant->phone ?? 'Non renseigné' }}</p>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Cours assignés ({{ $enseignant->cours->count() }})</h5>
                @if($enseignant->cours->count() > 0)
                    <div class="list-group">
                        @foreach($enseignant->cours as $cours)
                            <div class="list-group-item">
                                <strong>{{ $cours->nom }}</strong> ({{ $cours->code }}) - Coef: {{ $cours->coefficient }}
                                <div class="mt-2">
                                    <small class="text-muted">Classes: 
                                        @foreach($cours->classes as $classe)
                                            <span class="badge bg-secondary">{{ $classe->nom }}</span>
                                        @endforeach
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">Aucun cours assigné</p>
                @endif
            </div>
        </div>
        
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Classes ({{ $classes->count() }})</h5>
                @if($classes->count() > 0)
                    <div class="list-group">
                        @foreach($classes as $classe)
                            <div class="list-group-item">
                                <strong>{{ $classe->nom }}</strong>
                                <div class="small text-muted">
                                    {{ $classe->filiere->nom ?? '' }} - {{ $classe->niveau->nom ?? '' }}
                                    <br>
                                    Étudiants: {{ $classe->etudiants->count() }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">Aucune classe assignée</p>
                @endif
            </div>
        </div>
        
        @if($calendrier->count() > 0)
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">📅 Calendrier des Cours</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Jour</th>
                                <th>Heure</th>
                                <th>Cours</th>
                                <th>Classe</th>
                                <th>Salle</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($calendrier as $item)
                                <tr>
                                    <td>{{ $item->jour_semaine }}</td>
                                    <td>{{ date('H:i', strtotime($item->heure_debut)) }} - {{ date('H:i', strtotime($item->heure_fin)) }}</td>
                                    <td>{{ $item->cours->nom ?? ($item->description ?? '-') }}</td>
                                    <td>{{ $item->classe->nom ?? '-' }}</td>
                                    <td>{{ $item->salle ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection


