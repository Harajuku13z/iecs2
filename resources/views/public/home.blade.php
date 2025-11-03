@extends('layouts.app')

@section('title', 'Accueil - IESCA')

@section('content')
<!-- Hero Section -->
<section class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">{{ \App\Models\Setting::get('homepage_title', 'Bienvenue à l\'IESCA') }}</h1>
                <p class="lead mb-4">
                    Institut d'Enseignement Supérieur de la Côte Africaine - Formez-vous dans les meilleures conditions
                </p>
                <div class="d-flex gap-3">
                    <a href="{{ route('admission') }}" class="btn btn-light btn-lg">
                        📝 S'inscrire maintenant
                    </a>
                    <a href="{{ route('formations') }}" class="btn btn-outline-light btn-lg">
                        📚 Nos formations
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="https://via.placeholder.com/600x400" alt="IESCA Campus" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Pourquoi Choisir l'IESCA ?</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 text-center border-0 shadow-sm">
                    <div class="card-body">
                        <div class="display-4 mb-3">👨‍🏫</div>
                        <h5 class="card-title">Enseignants Qualifiés</h5>
                        <p class="card-text">
                            Des professeurs expérimentés et dévoués pour votre réussite
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center border-0 shadow-sm">
                    <div class="card-body">
                        <div class="display-4 mb-3">🏢</div>
                        <h5 class="card-title">Infrastructure Moderne</h5>
                        <p class="card-text">
                            Laboratoires équipés et salles de classe climatisées
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center border-0 shadow-sm">
                    <div class="card-body">
                        <div class="display-4 mb-3">🎓</div>
                        <h5 class="card-title">Diplômes Reconnus</h5>
                        <p class="card-text">
                            Formations certifiées et reconnues internationalement
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Formations Section -->
<section class="bg-light py-5">
    <div class="container">
        <h2 class="text-center mb-5">Nos Filières</h2>
        <div class="row g-4">
            @foreach(\App\Models\Filiere::all() as $filiere)
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $filiere->nom }}</h5>
                            <p class="card-text">{{ $filiere->description }}</p>
                            <a href="{{ route('formations') }}" class="btn btn-primary">En savoir plus →</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="bg-primary text-white py-5">
    <div class="container text-center">
        <h2 class="mb-4">Prêt à Commencer Votre Aventure ?</h2>
        <p class="lead mb-4">
            Les inscriptions sont ouvertes à partir du {{ \App\Models\Setting::get('inscription_start_date', '2025-01-15') }}
        </p>
        <a href="{{ route('admission') }}" class="btn btn-light btn-lg">
            Déposer ma candidature
        </a>
    </div>
</section>
@endsection

