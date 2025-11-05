@extends('layouts.enseignant')

@section('teacher_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>📤 Envoyer une Notification</h3>
    <a href="{{ route('enseignant.notifications.index') }}" class="btn btn-sm btn-outline-secondary">← Retour</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('enseignant.notifications.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="titre" class="form-label">Titre *</label>
                <input type="text" class="form-control @error('titre') is-invalid @enderror" 
                       id="titre" name="titre" value="{{ old('titre') }}" required>
                @error('titre')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="contenu" class="form-label">Contenu *</label>
                <textarea class="form-control @error('contenu') is-invalid @enderror" 
                          id="contenu" name="contenu" rows="5" required>{{ old('contenu') }}</textarea>
                @error('contenu')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="type" class="form-label">Type *</label>
                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                        <option value="info" {{ old('type') == 'info' ? 'selected' : '' }}>Information</option>
                        <option value="warning" {{ old('type') == 'warning' ? 'selected' : '' }}>Avertissement</option>
                        <option value="success" {{ old('type') == 'success' ? 'selected' : '' }}>Succès</option>
                        <option value="danger" {{ old('type') == 'danger' ? 'selected' : '' }}>Urgent</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="classe_id" class="form-label">Classe *</label>
                    <select class="form-select @error('classe_id') is-invalid @enderror" id="classe_id" name="classe_id" required>
                        <option value="">Sélectionner une classe</option>
                        @foreach($classes as $classe)
                            <option value="{{ $classe->id }}" {{ old('classe_id') == $classe->id ? 'selected' : '' }}>
                                {{ $classe->nom }} - {{ optional($classe->filiere)->nom }} ({{ optional($classe->niveau)->nom }})
                            </option>
                        @endforeach
                    </select>
                    @error('classe_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">📤 Envoyer la notification</button>
                <a href="{{ route('enseignant.notifications.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection


