@extends('layouts.admin')

@section('title', 'Modifier Niveau')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Modifier Niveau</h1>
    <a href="{{ route('admin.niveaux.index') }}" class="btn btn-secondary">← Retour</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.niveaux.update', $niveau) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="nom" class="form-label">Nom du niveau *</label>
                <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                       id="nom" name="nom" value="{{ old('nom', $niveau->nom) }}" required>
                @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="ordre" class="form-label">Ordre *</label>
                <input type="number" class="form-control @error('ordre') is-invalid @enderror" 
                       id="ordre" name="ordre" value="{{ old('ordre', $niveau->ordre) }}" min="1" required>
                @error('ordre')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
        </form>
    </div>
</div>
@endsection

