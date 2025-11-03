@extends('layouts.app')

@section('title', 'Accueil - IESCA')

@section('content')
<style>
.hero-section {
    position: relative;
    height: 85vh;
    background: linear-gradient(135deg, rgba(165, 29, 42, 0.95) 0%, rgba(45, 49, 66, 0.95) 100%),
                url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=1920') center/cover;
    display: flex;
    align-items: center;
    color: white;
    overflow: hidden;
}

.hero-content {
    position: relative;
    z-index: 2;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at 30% 50%, rgba(255,255,255,0.1) 0%, transparent 50%);
}

.search-box {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    margin-top: 2rem;
}

.search-box .form-select,
.search-box .btn {
    height: 55px;
    border-radius: 10px;
}

.timeline-step {
    position: relative;
    padding-left: 3rem;
    padding-bottom: 2rem;
}

.timeline-step::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    width: 2px;
    height: 100%;
    background: linear-gradient(180deg, #a51d2a 0%, #2d3142 100%);
}

.timeline-step::after {
    content: '';
    position: absolute;
    left: -8px;
    top: 0;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: #a51d2a;
    border: 3px solid white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

.timeline-step:last-child::before {
    display: none;
}

.news-card {
    transition: all 0.3s ease;
    border: none;
    border-radius: 15px;
    overflow: hidden;
    height: 100%;
}

.news-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.news-card img {
    height: 220px;
    object-fit: cover;
}

.event-card {
    border-left: 4px solid #a51d2a;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.event-card:hover {
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transform: translateX(5px);
}

.stat-card {
    text-align: center;
    padding: 2rem;
    border-radius: 15px;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border: 1px solid #e9ecef;
}

.stat-number {
    font-size: 3rem;
    font-weight: 700;
    color: #a51d2a;
    line-height: 1;
}

.category-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-weight: 600;
}

.btn-harvard {
    background: #a51d2a;
    color: white;
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-harvard:hover {
    background: #8a1723;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(165, 29, 42, 0.3);
}

.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2d3142;
    position: relative;
    padding-bottom: 1rem;
    margin-bottom: 3rem;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 4px;
    background: linear-gradient(90deg, #a51d2a 0%, #2d3142 100%);
    border-radius: 2px;
}
</style>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto hero-content">
                <h1 class="display-3 fw-bold mb-4" data-aos="fade-up">
                    {{ \App\Models\Setting::get('homepage_title', 'Excellence Académique et Innovation') }}
                </h1>
                <p class="lead mb-4" data-aos="fade-up" data-aos-delay="100">
                    Institut d'Enseignement Supérieur de la Côte Africaine - Façonnons l'avenir de l'éducation
                </p>
                
                <!-- Search Box -->
                <div class="search-box" data-aos="fade-up" data-aos-delay="200">
                    <form action="{{ route('formations') }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <select class="form-select" name="filiere_id">
                                    <option value="">Choisir une filière</option>
                                    @foreach(\App\Models\Filiere::all() as $filiere)
                                        <option value="{{ $filiere->id }}">{{ $filiere->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select class="form-select" name="niveau_id">
                                    <option value="">Niveau d'études</option>
                                    @foreach(\App\Models\Niveau::orderBy('ordre')->get() as $niveau)
                                        <option value="{{ $niveau->id }}">{{ $niveau->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-harvard w-100">
                                    🔍 Rechercher ma formation
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3" data-aos="fade-up">
                <div class="stat-card">
                    <div class="stat-number">{{ \App\Models\Filiere::count() }}+</div>
                    <p class="text-muted mb-0 fw-semibold">Filières</p>
                </div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-card">
                    <div class="stat-number">{{ \App\Models\User::where('role', 'enseignant')->count() }}+</div>
                    <p class="text-muted mb-0 fw-semibold">Enseignants</p>
                </div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-card">
                    <div class="stat-number">{{ \App\Models\User::where('role', 'etudiant')->count() }}+</div>
                    <p class="text-muted mb-0 fw-semibold">Étudiants</p>
                </div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-card">
                    <div class="stat-number">95%</div>
                    <p class="text-muted mb-0 fw-semibold">Taux de réussite</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Admission Process -->
<section class="py-5">
    <div class="container">
        <h2 class="section-title text-center" data-aos="fade-up">Processus d'Admission</h2>
        
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="timeline-step" data-aos="fade-right">
                    <h4 class="fw-bold mb-2">1. Inscription en ligne</h4>
                    <p class="text-muted">Créez votre compte et soumettez votre dossier de candidature en ligne.</p>
                </div>
                
                <div class="timeline-step" data-aos="fade-right" data-aos-delay="100">
                    <h4 class="fw-bold mb-2">2. Vérification des documents</h4>
                    <p class="text-muted">Notre équipe administrative examine votre dossier sous 48h.</p>
                </div>
                
                <div class="timeline-step" data-aos="fade-right" data-aos-delay="200">
                    <h4 class="fw-bold mb-2">3. Étude du dossier</h4>
                    <p class="text-muted">Le comité d'admission évalue votre candidature.</p>
                </div>
                
                <div class="timeline-step" data-aos="fade-right" data-aos-delay="300">
                    <h4 class="fw-bold mb-2">4. Notification de décision</h4>
                    <p class="text-muted">Recevez votre décision d'admission par email.</p>
                </div>
                
                <div class="text-center mt-4" data-aos="fade-up" data-aos-delay="400">
                    <a href="{{ route('admission') }}" class="btn btn-harvard btn-lg">
                        📝 Commencer ma candidature
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- News Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title mb-0" data-aos="fade-up">Actualités IESCA</h2>
            <a href="#" class="btn btn-outline-secondary">Toutes les actualités →</a>
        </div>
        
        <div class="row g-4">
            @php
                $actualites = \App\Models\Actualite::publie()->recent()->take(3)->get();
            @endphp
            
            @if($actualites->count() > 0)
                @foreach($actualites as $actu)
                    <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="card news-card shadow-sm">
                            <img src="{{ $actu->image ?? 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=800' }}" 
                                 class="card-img-top" alt="{{ $actu->titre }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="category-badge bg-primary text-white">{{ $actu->categorie }}</span>
                                    <small class="text-muted">{{ $actu->date_publication->format('d/m/Y') }}</small>
                                </div>
                                <h5 class="card-title fw-bold">{{ $actu->titre }}</h5>
                                <p class="card-text text-muted">{{ Str::limit($actu->description, 100) }}</p>
                                <a href="#" class="btn btn-link text-decoration-none p-0">Lire la suite →</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <h5>📰 Aucune actualité pour le moment</h5>
                        <p class="mb-0">Revenez bientôt pour découvrir nos dernières nouvelles !</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- Events Calendar -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title mb-0" data-aos="fade-up">Calendrier des Événements</h2>
            <a href="#" class="btn btn-outline-secondary">Voir le calendrier →</a>
        </div>
        
        <div class="row">
            <div class="col-lg-10 mx-auto">
                @php
                    $evenements = \App\Models\Evenement::publie()->aVenir()->take(4)->get();
                @endphp
                
                @if($evenements->count() > 0)
                    @foreach($evenements as $event)
                        <div class="card event-card shadow-sm mb-3" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-2 text-center border-end">
                                        <div class="text-danger fw-bold" style="font-size: 2rem;">{{ $event->date_debut->format('d') }}</div>
                                        <div class="text-muted">{{ $event->date_debut->format('M Y') }}</div>
                                    </div>
                                    <div class="col-md-7">
                                        <h5 class="fw-bold mb-1">{{ $event->titre }}</h5>
                                        <p class="text-muted mb-2">{{ $event->description }}</p>
                                        @if($event->lieu)
                                            <small class="text-muted">📍 {{ $event->lieu }}</small>
                                        @endif
                                    </div>
                                    <div class="col-md-3 text-end">
                                        <span class="badge bg-secondary mb-2">{{ $event->type }}</span>
                                        <br>
                                        <small class="text-muted">🕐 {{ $event->date_debut->format('H:i') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="alert alert-info text-center">
                        <h5>📅 Aucun événement programmé</h5>
                        <p class="mb-0">Consultez régulièrement cette section pour ne rien manquer !</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5" style="background: linear-gradient(135deg, #a51d2a 0%, #2d3142 100%);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8 text-white" data-aos="fade-right">
                <h2 class="fw-bold mb-3">Prêt à rejoindre l'excellence ?</h2>
                <p class="lead mb-0">Les inscriptions sont ouvertes à partir du {{ \App\Models\Setting::get('inscription_start_date', '2025-01-15') }}</p>
            </div>
            <div class="col-md-4 text-end" data-aos="fade-left">
                <a href="{{ route('admission') }}" class="btn btn-light btn-lg px-4 py-3">
                    Postuler maintenant
                </a>
            </div>
        </div>
    </div>
</section>

<!-- AOS Animation Library -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 800,
    once: true
  });
</script>
@endsection
