@extends('layouts.admin')

@section('title', 'Nouveau Niveau')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Nouveau Niveau</h1>
    <a href="{{ route('admin.niveaux.index') }}" class="btn btn-secondary">← Retour</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.niveaux.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="nom" class="form-label">Nom du niveau *</label>
                <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                       id="nom" name="nom" value="{{ old('nom') }}" required>
                @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="ordre" class="form-label">Ordre *</label>
                <input type="number" class="form-control @error('ordre') is-invalid @enderror" 
                       id="ordre" name="ordre" value="{{ old('ordre', 1) }}" min="1" required>
                @error('ordre')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
        </form>
    </div>
</div>
@endsection

