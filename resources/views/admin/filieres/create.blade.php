@extends('layouts.admin')

@section('title', 'Nouvelle Filière')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Nouvelle Filière</h1>
    <a href="{{ route('admin.filieres.index') }}" class="btn btn-secondary">
        ← Retour
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.filieres.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="nom" class="form-label">Nom de la filière *</label>
                <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                       id="nom" name="nom" value="{{ old('nom') }}" required>
                @error('nom')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="4">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Photo de la filière</label>
                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                       id="image" name="image" accept="image/*">
                <small class="text-muted">Format: JPG, PNG, GIF. Max: 2MB</small>
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

