@extends('layouts.admin')

@section('title', 'Nouveau Cours')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Nouveau Cours</h1>
    <a href="{{ route('admin.cours.index') }}" class="btn btn-secondary">← Retour</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.cours.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="nom" class="form-label">Nom du cours *</label>
                <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                       id="nom" name="nom" value="{{ old('nom') }}" required>
                @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="code" class="form-label">Code *</label>
                <input type="text" class="form-control @error('code') is-invalid @enderror" 
                       id="code" name="code" value="{{ old('code') }}" required>
                @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="coefficient" class="form-label">Coefficient *</label>
                <input type="number" class="form-control @error('coefficient') is-invalid @enderror" 
                       id="coefficient" name="coefficient" value="{{ old('coefficient', 1) }}" min="1" required>
                @error('coefficient')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
        </form>
    </div>
</div>
@endsection

