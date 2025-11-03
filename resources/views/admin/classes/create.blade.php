@extends('layouts.admin')

@section('title', 'Nouvelle Classe')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Nouvelle Classe</h1>
    <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary">← Retour</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.classes.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="nom" class="form-label">Nom de la classe *</label>
                <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                       id="nom" name="nom" value="{{ old('nom') }}" required>
                @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="filiere_id" class="form-label">Filière *</label>
                <select class="form-select @error('filiere_id') is-invalid @enderror" id="filiere_id" name="filiere_id" required>
                    <option value="">Sélectionner une filière</option>
                    @foreach($filieres as $filiere)
                        <option value="{{ $filiere->id }}" {{ old('filiere_id') == $filiere->id ? 'selected' : '' }}>
                            {{ $filiere->nom }}
                        </option>
                    @endforeach
                </select>
                @error('filiere_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="niveau_id" class="form-label">Niveau *</label>
                <select class="form-select @error('niveau_id') is-invalid @enderror" id="niveau_id" name="niveau_id" required>
                    <option value="">Sélectionner un niveau</option>
                    @foreach($niveaux as $niveau)
                        <option value="{{ $niveau->id }}" {{ old('niveau_id') == $niveau->id ? 'selected' : '' }}>
                            {{ $niveau->nom }}
                        </option>
                    @endforeach
                </select>
                @error('niveau_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
        </form>
    </div>
</div>
@endsection

