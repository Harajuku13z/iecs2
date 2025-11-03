@extends('layouts.app')

@section('title', 'Nos Formations - IESCA')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Nos Formations</h1>
    
    <div class="row g-4">
        @foreach(\App\Models\Filiere::with('classes')->get() as $filiere)
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">{{ $filiere->nom }}</h4>
                    </div>
                    <div class="card-body">
                        <p class="card-text">{{ $filiere->description }}</p>
                        
                        <h6 class="mt-4">Niveaux disponibles:</h6>
                        <ul class="list-group list-group-flush">
                            @foreach($filiere->classes as $classe)
                                <li class="list-group-item">
                                    {{ $classe->nom }} - {{ $classe->niveau->nom }}
                                </li>
                            @endforeach
                        </ul>
                        
                        <div class="mt-4">
                            <a href="{{ route('admission') }}" class="btn btn-primary">
                                Postuler pour cette filière
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

