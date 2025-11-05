@extends('layouts.admin')

@section('title', 'Modifier Candidature #' . $candidature->id)

@section('content')
<h1 class="mb-4">Modifier la Candidature #{{ $candidature->id }}</h1>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.candidatures.update', $candidature) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PATCH')

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Statut</label>
                    <select name="statut" class="form-select" required>
                        @foreach(['soumis'=>'Soumis','verifie'=>'Vérifié','admis'=>'Admis','rejete'=>'Rejeté'] as $val => $label)
                            <option value="{{ $val }}" @selected($candidature->statut===$val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Date d'évaluation</label>
                    <input type="datetime-local" name="evaluation_date" value="{{ $candidature->evaluation_date ? \Carbon\Carbon::parse($candidature->evaluation_date)->format('Y-m-d\TH:i') : '' }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Filière</label>
                    <select name="filiere_id" id="selFiliere" class="form-select">
                        <option value="">Sélectionnez</option>
                        @foreach(\App\Models\Filiere::all() as $f)
                            <option value="{{ $f->id }}" @selected($candidature->filiere_id==$f->id)>{{ $f->nom }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row g-3 mt-2">
                <div class="col-md-6">
                    <label class="form-label">Spécialité</label>
                    <select name="specialite_id" id="selSpecialite" class="form-select">
                        <option value="">Sélectionnez</option>
                        @foreach(\App\Models\Specialite::with('filiere')->get() as $sp)
                            <option value="{{ $sp->id }}" data-filiere="{{ $sp->filiere_id }}" @selected($candidature->specialite_id==$sp->id)>{{ $sp->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Classe (si admis)</label>
                    <select name="classe_id" id="selClasse" class="form-select">
                        <option value="">Sélectionner une classe</option>
                        @foreach($classes as $classe)
                            <option value="{{ $classe->id }}" 
                                    data-filiere="{{ $classe->filiere_id }}" 
                                    data-niveau="{{ $classe->niveau_id }}"
                                    @selected(optional($candidature->user)->classe_id==$classe->id || $candidature->classe_id==$classe->id)>
                                {{ $classe->nom }} ({{ optional($classe->niveau)->nom }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row g-3 mt-2">
                <div class="col-md-4">
                    <label class="form-label">Téléphone</label>
                    <input type="text" name="phone" class="form-control" value="{{ $candidature->user->phone }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Contact (Nom)</label>
                    <input type="text" name="contact_name" class="form-control" value="{{ $candidature->user->contact_name }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Contact (Téléphone)</label>
                    <input type="text" name="contact_phone" class="form-control" value="{{ $candidature->user->contact_phone }}">
                </div>
            </div>

            <div class="mt-3">
                <label class="form-label">Commentaire admin</label>
                <textarea name="commentaire_admin" class="form-control" rows="3">{{ $candidature->commentaire_admin }}</textarea>
            </div>

            <h5 class="mt-4">Photo de profil</h5>
            <div class="mb-3">
                <input type="file" name="profile_photo" class="form-control" accept=".jpg,.jpeg,.png" />
            </div>

            <h5 class="mt-4">Mettre à jour des documents (optionnel)</h5>
            <div class="row g-3">
                @php $fields = [
                    'doc_identite' => "Pièce d'identité",
                    'doc_diplome' => 'Diplôme',
                    'doc_releve' => 'Relevé de notes',
                    'doc_lettre' => 'Lettre de motivation (fichier)',
                    'doc_photo' => 'Photo',
                    'doc_cv' => 'CV',
                ]; @endphp
                @foreach($fields as $key => $label)
                    <div class="col-md-6">
                        <label class="form-label">{{ $label }}</label>
                        <input type="file" name="{{ $key }}" class="form-control" />
                    </div>
                @endforeach
            </div>

            <div class="mt-3">
                <label class="form-label">Lettre de motivation (texte)</label>
                @php
                    $docs = $candidature->documents ? json_decode($candidature->documents, true) : [];
                    $lettreDoc = collect($docs)->firstWhere('key', 'doc_lettre');
                    $motivationText = $lettreDoc['text'] ?? '';
                @endphp
                <textarea name="motivation_text" class="form-control" rows="5" placeholder="Écrivez votre lettre ici si vous ne l'uploadez pas...">{{ $motivationText }}</textarea>
                <small class="text-muted">Si vous remplissez ce champ, un PDF sera généré automatiquement.</small>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Enregistrer</button>
                <a href="{{ route('admin.candidatures.show', $candidature) }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selFiliere = document.getElementById('selFiliere');
    const selSpecialite = document.getElementById('selSpecialite');
    const selClasse = document.getElementById('selClasse');
    
    function filterSpecialites() {
        const filiereId = selFiliere.value;
        Array.from(selSpecialite.options).forEach(opt => {
            opt.style.display = !filiereId || opt.dataset.filiere == filiereId ? '' : 'none';
        });
        if (!filiereId) selSpecialite.value = '';
        filterClasses();
    }
    
    function filterClasses() {
        const filiereId = selFiliere.value;
        const specialiteId = selSpecialite.value;
        Array.from(selClasse.options).forEach(opt => {
            if (opt.value === '') return;
            const matchFiliere = !filiereId || opt.dataset.filiere == filiereId;
            opt.style.display = matchFiliere ? '' : 'none';
        });
    }
    
    selFiliere.addEventListener('change', filterSpecialites);
    selSpecialite.addEventListener('change', filterClasses);
    filterSpecialites();
});
</script>
@endsection


