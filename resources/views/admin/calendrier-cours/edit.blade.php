@extends('layouts.admin')

@section('title', 'Modifier Cours du Calendrier')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>✏️ Modifier Cours du Calendrier</h1>
    <a href="{{ route('admin.calendrier-cours.index') }}" class="btn btn-secondary">
        ← Retour
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.calendrier-cours.update', $calendrierCours) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="classe_id" class="form-label">Classe *</label>
                    <select class="form-select @error('classe_id') is-invalid @enderror" id="classe_id" name="classe_id" required>
                        <option value="">Sélectionner une classe</option>
                        @foreach($classes as $classe)
                            <option value="{{ $classe->id }}" {{ old('classe_id', $calendrierCours->classe_id) == $classe->id ? 'selected' : '' }}>
                                {{ $classe->nom }} - {{ optional($classe->filiere)->nom }} ({{ optional($classe->niveau)->nom }})
                            </option>
                        @endforeach
                    </select>
                    @error('classe_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="cours_id" class="form-label">Cours (optionnel)</label>
                    <select class="form-select @error('cours_id') is-invalid @enderror" id="cours_id" name="cours_id">
                        <option value="">Aucun cours spécifique</option>
                        @foreach($cours as $c)
                            <option value="{{ $c->id }}" {{ old('cours_id', $calendrierCours->cours_id) == $c->id ? 'selected' : '' }}>
                                {{ $c->nom }} ({{ $c->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('cours_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="jour_semaine" class="form-label">Jour de la semaine *</label>
                    <select class="form-select @error('jour_semaine') is-invalid @enderror" id="jour_semaine" name="jour_semaine" required>
                        <option value="">Sélectionner un jour</option>
                        <option value="Lundi" {{ old('jour_semaine', $calendrierCours->jour_semaine) == 'Lundi' ? 'selected' : '' }}>Lundi</option>
                        <option value="Mardi" {{ old('jour_semaine', $calendrierCours->jour_semaine) == 'Mardi' ? 'selected' : '' }}>Mardi</option>
                        <option value="Mercredi" {{ old('jour_semaine', $calendrierCours->jour_semaine) == 'Mercredi' ? 'selected' : '' }}>Mercredi</option>
                        <option value="Jeudi" {{ old('jour_semaine', $calendrierCours->jour_semaine) == 'Jeudi' ? 'selected' : '' }}>Jeudi</option>
                        <option value="Vendredi" {{ old('jour_semaine', $calendrierCours->jour_semaine) == 'Vendredi' ? 'selected' : '' }}>Vendredi</option>
                        <option value="Samedi" {{ old('jour_semaine', $calendrierCours->jour_semaine) == 'Samedi' ? 'selected' : '' }}>Samedi</option>
                        <option value="Dimanche" {{ old('jour_semaine', $calendrierCours->jour_semaine) == 'Dimanche' ? 'selected' : '' }}>Dimanche</option>
                    </select>
                    @error('jour_semaine')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="heure_debut" class="form-label">Heure de début *</label>
                    <input type="time" class="form-control @error('heure_debut') is-invalid @enderror" 
                           id="heure_debut" name="heure_debut" 
                           value="{{ old('heure_debut', date('H:i', strtotime($calendrierCours->heure_debut))) }}" required>
                    @error('heure_debut')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="heure_fin" class="form-label">Heure de fin *</label>
                    <input type="time" class="form-control @error('heure_fin') is-invalid @enderror" 
                           id="heure_fin" name="heure_fin" 
                           value="{{ old('heure_fin', date('H:i', strtotime($calendrierCours->heure_fin))) }}" required>
                    @error('heure_fin')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="salle" class="form-label">Salle</label>
                    <input type="text" class="form-control @error('salle') is-invalid @enderror" 
                           id="salle" name="salle" value="{{ old('salle', $calendrierCours->salle) }}" placeholder="Ex: Salle 101">
                    @error('salle')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="enseignant" class="form-label">Enseignant</label>
                    <input type="text" class="form-control @error('enseignant') is-invalid @enderror" 
                           id="enseignant" name="enseignant" value="{{ old('enseignant', $calendrierCours->enseignant) }}" placeholder="Nom de l'enseignant">
                    @error('enseignant')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description (optionnel)</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="3" placeholder="Description du cours ou activité">{{ old('description', $calendrierCours->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
                <a href="{{ route('admin.calendrier-cours.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection

