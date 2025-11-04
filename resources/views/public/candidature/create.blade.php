@extends('layouts.app')

@section('title', 'Soumettre ma candidature')

@section('content')
<div class="container py-5">
    <h1 class="mb-1">Soumettre ma candidature</h1>
    <p class="text-muted mb-4">Complétez vos informations et déposez vos pièces</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('candidature.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <h5 class="mb-3" style="font-weight:700;">Informations</h5>
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="phone" class="form-control" value="{{ auth()->user()->phone }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Contact (Nom)</label>
                        <input type="text" name="contact_name" class="form-control" value="{{ auth()->user()->contact_name }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Contact (Téléphone)</label>
                        <input type="text" name="contact_phone" class="form-control" value="{{ auth()->user()->contact_phone }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Photo de profil</label>
                        <input type="file" name="profile_photo" class="form-control" accept=".jpg,.jpeg,.png" />
                    </div>
                </div>

                <h5 class="mb-2" style="font-weight:700;">Pièces jointes</h5>
                <div class="row g-3">
                    @php $fields = [
                        'doc_identite' => "Pièce d'identité",
                        'doc_diplome' => 'Diplôme',
                        'doc_releve' => 'Relevé de notes',
                        'doc_lettre' => 'Lettre de motivation (si vous ne saisissez pas de texte)',
                        'doc_photo' => 'Photo',
                        'doc_cv' => 'CV (optionnel)',
                    ]; @endphp
                    @foreach($fields as $name => $label)
                        <div class="col-md-6">
                            <label class="form-label">{{ $label }}</label>
                            <input type="file" name="{{ $name }}" class="form-control" />
                        </div>
                    @endforeach
                </div>

                <div class="mt-3">
                    <label class="form-label">Lettre de motivation (texte)</label>
                    <textarea name="motivation_text" class="form-control" rows="5" placeholder="Écrivez votre lettre ici si vous ne l'uploadez pas..."></textarea>
                    <small class="text-muted">Si vous remplissez ce champ, un PDF sera généré automatiquement.</small>
                </div>
                <div class="mt-4">
                    <button class="btn btn-site">Soumettre</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


