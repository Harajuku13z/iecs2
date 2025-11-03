@extends('layouts.admin')

@section('title', 'Modifier Utilisateur')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Modifier Utilisateur</h1>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">← Retour</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Nom *</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email *</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe (laisser vide pour ne pas changer)</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                       id="password" name="password">
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Rôle *</label>
                <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                    <option value="candidat" {{ $user->role === 'candidat' ? 'selected' : '' }}>Candidat</option>
                    <option value="etudiant" {{ $user->role === 'etudiant' ? 'selected' : '' }}>Étudiant</option>
                    <option value="enseignant" {{ $user->role === 'enseignant' ? 'selected' : '' }}>Enseignant</option>
                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="classe_id" class="form-label">Classe (pour étudiants)</label>
                <select class="form-select" id="classe_id" name="classe_id">
                    <option value="">Aucune</option>
                    @foreach($classes as $classe)
                        <option value="{{ $classe->id }}" {{ $user->classe_id == $classe->id ? 'selected' : '' }}>
                            {{ $classe->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
        </form>
    </div>
</div>
@endsection

