@extends('layouts.admin')

@section('title', 'Envoyer une Notification')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>📤 Envoyer une Notification</h1>
    <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary">← Retour</a>
</div>

<div class="card">
    <div class="card-body">
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        <form action="{{ route('admin.notifications.store') }}" method="POST">
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
                        <option value="message" {{ old('type') == 'message' ? 'selected' : '' }}>Message personnel</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="destinataire_type" class="form-label">Destinataire *</label>
                    <select class="form-select @error('destinataire_type') is-invalid @enderror" id="destinataire_type" name="destinataire_type" required>
                        <option value="all" {{ old('destinataire_type') == 'all' ? 'selected' : '' }}>Tous les étudiants</option>
                        <option value="classe" {{ old('destinataire_type') == 'classe' ? 'selected' : '' }}>Une classe</option>
                        <option value="user" {{ old('destinataire_type') == 'user' ? 'selected' : '' }}>Un étudiant spécifique</option>
                        <option value="role" {{ old('destinataire_type') == 'role' ? 'selected' : '' }}>Par rôle (Étudiants ou Candidats)</option>
                    </select>
                    @error('destinataire_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mb-3" id="classeContainer" style="display: none;">
                <label for="classe_id" class="form-label">Classe *</label>
                <select class="form-select @error('classe_id') is-invalid @enderror" id="classe_id" name="classe_id">
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
            
            <div class="mb-3" id="userContainer" style="display: none;">
                <label for="user_id" class="form-label">Étudiant / Candidat *</label>
                <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id">
                    <option value="">Sélectionner</option>
                    <optgroup label="Étudiants">
                        @foreach($etudiants as $u)
                            <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->email }})
                            </option>
                        @endforeach
                    </optgroup>
                    <optgroup label="Candidats">
                        @foreach($candidats as $u)
                            <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->email }})
                            </option>
                        @endforeach
                    </optgroup>
                </select>
                @error('user_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3" id="roleContainer" style="display: none;">
                <label for="role_name" class="form-label">Rôle *</label>
                <select class="form-select @error('role_name') is-invalid @enderror" id="role_name" name="role_name">
                    <option value="">Sélectionner un rôle</option>
                    <option value="etudiant" {{ old('role_name')=='etudiant' ? 'selected' : '' }}>Étudiants</option>
                    <option value="candidat" {{ old('role_name')=='candidat' ? 'selected' : '' }}>Candidats</option>
                </select>
                @error('role_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3" id="emailContainer" style="display: none;">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="envoyer_email" name="envoyer_email" value="1" {{ old('envoyer_email') ? 'checked' : '' }}>
                    <label class="form-check-label" for="envoyer_email">
                        Envoyer également par email
                    </label>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">📤 Envoyer la notification</button>
                <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('destinataire_type').addEventListener('change', function() {
    const type = this.value;
    const classeContainer = document.getElementById('classeContainer');
    const userContainer = document.getElementById('userContainer');
    const roleContainer = document.getElementById('roleContainer');
    const emailContainer = document.getElementById('emailContainer');
    
    classeContainer.style.display = type === 'classe' ? 'block' : 'none';
    userContainer.style.display = type === 'user' ? 'block' : 'none';
    roleContainer.style.display = type === 'role' ? 'block' : 'none';
    emailContainer.style.display = (type === 'user' || type==='role') ? 'block' : 'none';
    
    if (type === 'classe') {
        document.getElementById('classe_id').required = true;
        document.getElementById('user_id').required = false;
        document.getElementById('role_name').required = false;
    } else if (type === 'user') {
        document.getElementById('user_id').required = true;
        document.getElementById('classe_id').required = false;
        document.getElementById('role_name').required = false;
    } else if (type === 'role') {
        document.getElementById('role_name').required = true;
        document.getElementById('user_id').required = false;
        document.getElementById('classe_id').required = false;
    } else {
        document.getElementById('classe_id').required = false;
        document.getElementById('user_id').required = false;
        document.getElementById('role_name').required = false;
    }
});

// Initialiser au chargement
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('destinataire_type').dispatchEvent(new Event('change'));
});
</script>
@endsection



