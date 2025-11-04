@extends('layouts.app')

@section('title', $filiere->nom . ' - IESCA')

@section('content')
<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius:8px; overflow:hidden;">
                @if($filiere->image)
                    <img src="{{ asset('storage/' . $filiere->image) }}" alt="{{ $filiere->nom }}" style="width:100%; height:320px; object-fit:cover;">
                @endif
                <div class="card-body">
                    <h1 class="mb-2" style="font-weight:800; font-size:1.6rem;">{{ $filiere->nom }}</h1>
                    <p class="text-muted">{{ $filiere->description }}</p>

                    @if($filiere->specialites && $filiere->specialites->count())
                        <h5 class="mt-4" style="font-weight:700;">Spécialités</h5>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($filiere->specialites as $sp)
                                <span class="badge bg-light text-dark border">{{ $sp->nom }}</span>
                            @endforeach
                        </div>
                    @endif

                    @if($filiere->classes && $filiere->classes->count())
                        <h5 class="mt-4" style="font-weight:700;">Classes disponibles</h5>
                        <ul class="list-group list-group-flush">
                            @foreach($filiere->classes as $classe)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $classe->nom }}</strong>
                                        <small class="text-muted d-block">Niveau: {{ optional($classe->niveau)->nom }}</small>
                                    </div>
                                    <a href="{{ route('admission') }}" class="btn btn-site">Créer ma candidature</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="mb-3" style="font-weight:700;">Prêt à candidater ?</h5>
                    <p class="text-muted">Soumettez votre candidature en quelques minutes.</p>
                    <a href="{{ route('admission') }}" class="btn btn-site w-100 mb-2">Soumettre ma candidature</a>
                    <a href="{{ route('formations') }}" class="btn btn-outline-secondary w-100">← Retour aux formations</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


