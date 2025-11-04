@extends('layouts.admin')

@section('title', 'Modifier Actualité')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>📰 Modifier Actualité</h1>
    <a href="{{ route('admin.actualites.index') }}" class="btn btn-secondary">
        ← Retour
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.actualites.update', $actualite) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="titre" class="form-label">Titre *</label>
                <input type="text" class="form-control @error('titre') is-invalid @enderror" 
                       id="titre" name="titre" value="{{ old('titre', $actualite->titre) }}" required>
                @error('titre')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description *</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="3" required>{{ old('description', $actualite->description) }}</textarea>
                <small class="text-muted">Description courte qui apparaîtra dans les listes</small>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="contenu" class="form-label">Contenu *</label>
                <textarea class="form-control @error('contenu') is-invalid @enderror" 
                          id="contenu" name="contenu" rows="10" required>{{ old('contenu', $actualite->contenu) }}</textarea>
                <small class="text-muted">Contenu complet de l'actualité</small>
                @error('contenu')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="categorie" class="form-label">Catégorie *</label>
                    <select class="form-select @error('categorie') is-invalid @enderror" 
                            id="categorie" name="categorie" required>
                        <option value="general" {{ old('categorie', $actualite->categorie) == 'general' ? 'selected' : '' }}>Général</option>
                        <option value="academique" {{ old('categorie', $actualite->categorie) == 'academique' ? 'selected' : '' }}>Académique</option>
                        <option value="evenement" {{ old('categorie', $actualite->categorie) == 'evenement' ? 'selected' : '' }}>Événement</option>
                        <option value="admission" {{ old('categorie', $actualite->categorie) == 'admission' ? 'selected' : '' }}>Admission</option>
                        <option value="vie-etudiante" {{ old('categorie', $actualite->categorie) == 'vie-etudiante' ? 'selected' : '' }}>Vie Étudiante</option>
                    </select>
                    @error('categorie')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="date_publication" class="form-label">Date de Publication *</label>
                    <input type="date" class="form-control @error('date_publication') is-invalid @enderror" 
                           id="date_publication" name="date_publication" 
                           value="{{ old('date_publication', $actualite->date_publication->format('Y-m-d')) }}" required>
                    @error('date_publication')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                @if($actualite->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $actualite->image) }}" alt="{{ $actualite->titre }}" 
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
                       {{ old('publie', $actualite->publie) ? 'checked' : '' }}>
                <label class="form-check-label" for="publie">
                    Publier immédiatement
                </label>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
                <a href="{{ route('admin.actualites.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection

