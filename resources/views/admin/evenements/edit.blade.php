@extends('layouts.admin')

@section('title', 'Modifier Événement')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>📅 Modifier Événement</h1>
    <a href="{{ route('admin.evenements.index') }}" class="btn btn-secondary">
        ← Retour
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.evenements.update', $evenement) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="titre" class="form-label">Titre *</label>
                <input type="text" class="form-control @error('titre') is-invalid @enderror" 
                       id="titre" name="titre" value="{{ old('titre', $evenement->titre) }}" required>
                @error('titre')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description *</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="5" required>{{ old('description', $evenement->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="type" class="form-label">Type *</label>
                    <select class="form-select @error('type') is-invalid @enderror" 
                            id="type" name="type" required>
                        <option value="conference" {{ old('type', $evenement->type) == 'conference' ? 'selected' : '' }}>Conférence</option>
                        <option value="seminaire" {{ old('type', $evenement->type) == 'seminaire' ? 'selected' : '' }}>Séminaire</option>
                        <option value="atelier" {{ old('type', $evenement->type) == 'atelier' ? 'selected' : '' }}>Atelier</option>
                        <option value="formation" {{ old('type', $evenement->type) == 'formation' ? 'selected' : '' }}>Formation</option>
                        <option value="competition" {{ old('type', $evenement->type) == 'competition' ? 'selected' : '' }}>Compétition</option>
                        <option value="autre" {{ old('type', $evenement->type) == 'autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="lieu" class="form-label">Lieu</label>
                    <input type="text" class="form-control @error('lieu') is-invalid @enderror" 
                           id="lieu" name="lieu" value="{{ old('lieu', $evenement->lieu) }}" 
                           placeholder="Ex: Salle de conférence, Campus principal">
                    @error('lieu')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="date_debut" class="form-label">Date de Début *</label>
                    <input type="date" class="form-control @error('date_debut') is-invalid @enderror" 
                           id="date_debut" name="date_debut" 
                           value="{{ old('date_debut', $evenement->date_debut->format('Y-m-d')) }}" required>
                    @error('date_debut')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="heure_debut" class="form-label">Heure de Début</label>
                    <input type="time" class="form-control" 
                           id="heure_debut" name="heure_debut" 
                           value="{{ old('heure_debut', $evenement->date_debut->format('H:i')) }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="date_fin" class="form-label">Date de Fin</label>
                    <input type="date" class="form-control @error('date_fin') is-invalid @enderror" 
                           id="date_fin" name="date_fin" 
                           value="{{ old('date_fin', $evenement->date_fin ? $evenement->date_fin->format('Y-m-d') : '') }}">
                    @error('date_fin')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="heure_fin" class="form-label">Heure de Fin</label>
                    <input type="time" class="form-control" 
                           id="heure_fin" name="heure_fin" 
                           value="{{ old('heure_fin', $evenement->date_fin ? $evenement->date_fin->format('H:i') : '17:00') }}">
                </div>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                @if($evenement->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $evenement->image) }}" alt="{{ $evenement->titre }}" 
                             style="max-width: 300px; max-height: 200px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd;">
                        <p class="text-muted mt-1"><small>Image actuelle</small></p>
                    </div>
                @endif
                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                       id="image" name="image" accept="image/*">
                <small class="text-muted">Format: JPG, PNG, GIF. Max: 2MB. Laisser vide pour conserver l'image actuelle.</small>
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="publie" name="publie" 
                       {{ old('publie', $evenement->publie) ? 'checked' : '' }}>
                <label class="form-check-label" for="publie">
                    Publier immédiatement
                </label>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
                <a href="{{ route('admin.evenements.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection

