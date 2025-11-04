@extends('layouts.admin')

@section('title', 'Nouvelle Candidature')

@section('content')
<h1 class="mb-4">Créer une Candidature</h1>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.candidatures.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <h5 class="mb-3">Candidat</h5>
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Mode</label>
                    <select name="user_mode" id="user_mode" class="form-select">
                        <option value="existing">Sélectionner un utilisateur existant</option>
                        <option value="new">Créer un nouveau candidat</option>
                    </select>
                </div>
                <div class="col-md-8" id="existing_user_block">
                    <label class="form-label">Utilisateur existant</label>
                    <select name="user_id" class="form-select">
                        <option value="">-- Sélectionner --</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row g-3 mt-2" id="new_user_block" style="display:none;">
                <div class="col-md-3">
                    <label class="form-label">Nom</label>
                    <input type="text" name="nom" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Prénom</label>
                    <input type="text" name="prenom" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Téléphone</label>
                    <input type="text" name="telephone" class="form-control">
                </div>
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

            <h5 class="mb-2">Documents</h5>
            <div class="mb-3">
                <label class="form-label">Ajouter des documents (PDF, Images, ZIP)</label>
                <input type="file" name="documents[]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png,.gif,.zip,.rar">
                <small class="text-muted">Vous pouvez sélectionner plusieurs fichiers.</small>
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
<script>
document.getElementById('user_mode').addEventListener('change', function(){
    const isNew = this.value === 'new';
    document.getElementById('new_user_block').style.display = isNew ? '' : 'none';
    document.getElementById('existing_user_block').style.display = isNew ? 'none' : '';
});
</script>
@endsection


