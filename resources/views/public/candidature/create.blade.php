@extends('layouts.app')

@section('title', 'Soumettre ma candidature')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Soumettre ma candidature</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('candidature.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    @php $fields = [
                        'doc_identite' => "Pièce d'identité",
                        'doc_diplome' => 'Diplôme',
                        'doc_releve' => 'Relevé de notes',
                        'doc_lettre' => 'Lettre de motivation',
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
                <div class="mt-4">
                    <button class="btn btn-site">Soumettre</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


