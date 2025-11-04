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
                    <label class="form-label">Classe (si admis)</label>
                    <select name="classe_id" class="form-select">
                        <option value="">Sélectionner une classe</option>
                        @foreach($classes as $classe)
                            <option value="{{ $classe->id }}" @selected(optional($candidature->user)->classe_id==$classe->id)>{{ $classe->nom }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-3">
                <label class="form-label">Commentaire admin</label>
                <textarea name="commentaire_admin" class="form-control" rows="3">{{ $candidature->commentaire_admin }}</textarea>
            </div>

            <h5 class="mt-4">Mettre à jour des documents (optionnel)</h5>
            <div class="row g-3">
                @php $fields = [
                    'doc_identite' => "Pièce d'identité",
                    'doc_diplome' => 'Diplôme',
                    'doc_releve' => 'Relevé de notes',
                    'doc_lettre' => 'Lettre de motivation',
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

            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Enregistrer</button>
                <a href="{{ route('admin.candidatures.show', $candidature) }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection


