@extends('layouts.admin')

@section('title', 'Modifier Filière')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Modifier Filière</h1>
    <a href="{{ route('admin.filieres.index') }}" class="btn btn-secondary">
        ← Retour
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.filieres.update', $filiere) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nom" class="form-label">Nom de la filière *</label>
                <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                       id="nom" name="nom" value="{{ old('nom', $filiere->nom) }}" required>
                @error('nom')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="4">{{ old('description', $filiere->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Photo de la filière</label>
                @if($filiere->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $filiere->image) }}" alt="{{ $filiere->nom }}" 
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

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
                <a href="{{ route('admin.filieres.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection

