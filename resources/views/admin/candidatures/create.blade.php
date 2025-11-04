@extends('layouts.admin')

@section('title', 'Nouvelle Candidature')

@section('content')
<h1 class="mb-4">Créer une Candidature</h1>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.candidatures.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Étudiant (utilisateur)</label>
                <select name="user_id" class="form-select" required>
                    <option value="">-- Sélectionner --</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Statut initial</label>
                <select name="statut" class="form-select" required>
                    <option value="soumis">Soumis</option>
                    <option value="verifie">Vérifié</option>
                    <option value="admis">Admis</option>
                    <option value="rejete">Rejeté</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Commentaire admin</label>
                <textarea name="commentaire_admin" class="form-control" rows="3"></textarea>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary" type="submit">Créer</button>
                <a href="{{ route('admin.candidatures.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection


