@extends('layouts.app')

@section('title', 'Accueil - IESCA')

@section('content')
<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Inter:wght@300;400;500;600;700&display=swap');

* {
    font-family: 'Inter', sans-serif;
}

h1, h2, h3, h4, h5, h6 {
    font-family: 'Playfair Display', serif;
}

/* Hero Section - Collé au header */
.hero-section {
    position: relative;
    min-height: 90vh;
    background: linear-gradient(135deg, rgba(166, 96, 96, 0.85) 0%, rgba(13, 13, 13, 0.85) 100%),
                url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=1920') center/cover;
    color: white;
    overflow: hidden;
    margin-top: 0;
    display: flex;
    align-items: center;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 50%, rgba(158, 90, 89, 0.15) 0%, transparent 50%),
        radial-gradient(circle at 80% 50%, rgba(166, 96, 96, 0.2) 0%, transparent 50%);
    animation: pulse 15s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.8; }
}

.hero-content {
    position: relative;
    z-index: 2;
    padding: 4rem 0;
}

.hero-title {
    font-size: clamp(2.5rem, 6vw, 5rem);
    font-weight: 900;
    line-height: 1.1;
    margin-bottom: 2rem;
    text-shadow: 2px 4px 20px rgba(0,0,0,0.3);
}

.hero-subtitle {
    font-size: clamp(1.1rem, 2vw, 1.5rem);
    font-weight: 300;
    opacity: 0.95;
    margin-bottom: 3rem;
    letter-spacing: 0.5px;
}

/* Premium Search Box */
.premium-search {
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    padding: 2.5rem;
    box-shadow: 
        0 25px 50px rgba(0,0,0,0.3),
        0 0 0 1px rgba(255,255,255,0.1) inset;
    transform: translateY(0);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.premium-search:hover {
    transform: translateY(-5px);
    box-shadow: 0 30px 60px rgba(0,0,0,0.4);
}

.search-input {
    height: 60px;
    border: 2px solid #e8e8e8;
    border-radius: 16px;
    padding: 0 1.5rem;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
}

.search-input:focus {
    border-color: var(--color-primary);
    box-shadow: 0 0 0 4px rgba(166, 96, 96, 0.1);
    outline: none;
}

.search-button {
    height: 60px;
    background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
    border: none;
    border-radius: 16px;
    color: white;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    box-shadow: 0 10px 30px rgba(166, 96, 96, 0.3);
}

.search-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 40px rgba(166, 96, 96, 0.4);
}


/* Premium Stats */
.stats-container {
    background: var(--color-light);
    padding: 5rem 0;
}

.stat-card {
    background: white;
    border-radius: 20px;
    padding: 3rem 2rem;
    text-align: center;
    box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    border: 1px solid rgba(166, 96, 96, 0.1);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, var(--color-primary), var(--color-secondary));
    transform: scaleX(0);
    transition: transform 0.4s ease;
}

.stat-card:hover::before {
    transform: scaleX(1);
}

.stat-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 60px rgba(166, 96, 96, 0.15);
}

.stat-number {
    font-size: 4rem;
    font-weight: 900;
    background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1;
    margin-bottom: 1rem;
}

.stat-label {
    font-size: 1.1rem;
    color: var(--color-dark);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* About Section */
.about-section {
    padding: 6rem 0;
    background: white;
}

.about-image {
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    height: 100%;
    min-height: 400px;
}

.about-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.about-content {
    display: flex;
    flex-direction: column;
    height: 100%;
}

.about-content h2 {
    font-size: 3rem;
    font-weight: 900;
    color: var(--color-black);
    margin-bottom: 2rem;
    margin-top: 0;
}

.about-content p {
    font-size: 1.1rem;
    line-height: 1.8;
    color: var(--color-dark);
    margin-bottom: 1.5rem;
}

.about-features {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
    margin-top: 2rem;
}

.feature-item {
    display: flex;
    align-items: start;
    gap: 1rem;
}

.feature-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    flex-shrink: 0;
}

/* Admission Timeline */
.admission-section {
    padding: 6rem 0;
    background: linear-gradient(135deg, var(--color-black) 0%, var(--color-dark) 100%);
    color: white;
}

.section-header {
    text-align: center;
    margin-bottom: 5rem;
}

.section-title {
    font-size: clamp(2.5rem, 5vw, 4rem);
    font-weight: 900;
    margin-bottom: 1rem;
    background: linear-gradient(135deg, white, rgba(255,255,255,0.8));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.section-subtitle {
    font-size: 1.2rem;
    opacity: 0.8;
    font-weight: 300;
}

.timeline-container {
    position: relative;
    max-width: 900px;
    margin: 0 auto;
}

.timeline-item {
    display: flex;
    margin-bottom: 4rem;
    position: relative;
}

.timeline-number {
    flex-shrink: 0;
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 900;
    margin-right: 2rem;
    box-shadow: 0 10px 30px rgba(166, 96, 96, 0.4);
    position: relative;
}

.timeline-number::after {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    width: 2px;
    height: 80px;
    background: linear-gradient(180deg, var(--color-secondary), transparent);
}

.timeline-item:last-child .timeline-number::after {
    display: none;
}

.timeline-content {
    flex: 1;
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 2rem;
    border: 1px solid rgba(255,255,255,0.1);
    transition: all 0.3s ease;
}

.timeline-content:hover {
    background: rgba(255,255,255,0.08);
    transform: translateX(10px);
}

.timeline-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.timeline-description {
    opacity: 0.8;
    line-height: 1.6;
}

/* Premium News Cards */
.news-section {
    padding: 6rem 0;
    background: var(--color-light);
}

.news-section .section-title {
    color: var(--color-black) !important;
}

.news-section .section-subtitle {
    color: var(--color-black) !important;
}

.news-section h2,
.news-section h3,
.news-section p {
    color: var(--color-black) !important;
}

.news-card {
    background: white;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
    border: 1px solid rgba(0,0,0,0.05);
}

.news-card:hover {
    transform: translateY(-15px) scale(1.02);
    box-shadow: 0 25px 60px rgba(0,0,0,0.15);
}

.news-image {
    height: 280px;
    overflow: hidden;
    position: relative;
}

.news-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
}

.news-card:hover .news-image img {
    transform: scale(1.1);
}

.news-badge {
    position: absolute;
    top: 1rem;
    left: 1rem;
    background: var(--color-primary);
    color: white;
    padding: 0.5rem 1.5rem;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.news-content {
    padding: 2rem;
}

.news-date {
    color: #999;
    font-size: 0.9rem;
    margin-bottom: 1rem;
}

.news-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: var(--color-black);
    line-height: 1.3;
}

.news-description {
    color: var(--color-dark);
    line-height: 1.7;
    margin-bottom: 1.5rem;
}

.read-more {
    color: var(--color-primary);
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: gap 0.3s ease;
}

.read-more:hover {
    gap: 1rem;
}

/* Events Section with Calendar */
.events-section {
    padding: 6rem 0;
    background: white;
}

.events-list {
    max-height: 600px;
    overflow-y: auto;
    padding-right: 1rem;
}

.events-list::-webkit-scrollbar {
    width: 6px;
}

.events-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.events-list::-webkit-scrollbar-thumb {
    background: var(--color-primary);
    border-radius: 10px;
}

.event-card {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    box-shadow: 0 5px 20px rgba(0,0,0,0.06);
    border-left: 5px solid var(--color-primary);
    transition: all 0.3s ease;
}

.event-card:hover {
    transform: translateX(10px);
    box-shadow: 0 10px 40px rgba(166, 96, 96, 0.15);
}

.event-date-badge {
    display: inline-block;
    background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.event-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--color-black);
    margin-bottom: 0.5rem;
}

.event-info {
    color: var(--color-dark);
    font-size: 0.9rem;
}

.calendar-container {
    background: white;
    border-radius: 24px;
    padding: 2rem;
    box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    border: 1px solid rgba(166, 96, 96, 0.1);
}

.calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.calendar-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--color-black);
}

.calendar-nav {
    display: flex;
    gap: 0.5rem;
}

.calendar-nav button {
    background: var(--color-primary);
    border: none;
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.calendar-nav button:hover {
    background: var(--color-secondary);
    transform: scale(1.1);
}

.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 0.5rem;
}

.calendar-day-header {
    text-align: center;
    font-weight: 600;
    color: var(--color-dark);
    padding: 0.5rem;
    font-size: 0.9rem;
}

.calendar-day {
    aspect-ratio: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 500;
}

.calendar-day:hover {
    background: var(--color-light);
}

.calendar-day.has-event {
    background: var(--color-primary);
    color: white;
    font-weight: 700;
}

.calendar-day.today {
    border: 2px solid var(--color-primary);
}

/* CTA Section */
.cta-section {
    padding: 6rem 0;
    background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-black) 100%);
    color: white;
    position: relative;
    overflow: hidden;
}

.cta-section::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(158, 90, 89, 0.2) 0%, transparent 70%);
    border-radius: 50%;
}

.cta-content {
    position: relative;
    z-index: 1;
}

.cta-title {
    font-size: clamp(2rem, 4vw, 3.5rem);
    font-weight: 900;
    margin-bottom: 1rem;
}

.cta-subtitle {
    font-size: 1.3rem;
    opacity: 0.9;
    font-weight: 300;
}

.cta-button {
    background: white;
    color: var(--color-primary);
    padding: 1.2rem 3rem;
    border-radius: 50px;
    font-size: 1.2rem;
    font-weight: 700;
    border: none;
    box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
}

.cta-button:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 50px rgba(0,0,0,0.4);
    color: var(--color-primary);
}

/* Responsive */
@media (max-width: 768px) {
    .hero-content {
        padding: 4rem 0 3rem;
    }
    
    .premium-search {
        padding: 1.5rem;
    }
    
    .search-input, .search-button {
        height: 50px;
    }
    
    .about-features {
        grid-template-columns: 1fr;
    }
    
    .timeline-item {
        flex-direction: column;
    }
    
    .timeline-number {
        margin-bottom: 1rem;
    }
}
</style>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="hero-content">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="text-center">
                        <h1 class="hero-title" data-aos="fade-up">
                            Façonnons l'Avenir<br>de l'Excellence
                        </h1>
                        <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="100">
                            Institut d'Enseignement Supérieur de la Côte Africaine
                        </p>
                    </div>
                    
                    <!-- Premium Search Box - Style Voyage -->
                    <div class="premium-search" data-aos="fade-up" data-aos-delay="200">
                        <div class="container">
                            <h4 class="text-center text-dark mb-4" style="font-weight: 700;">Trouvez Votre Formation Idéale</h4>
                            <form id="formationSearchForm" action="{{ route('formations') }}" method="GET">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label text-dark mb-2" style="font-weight: 600;">📚 Choisissez votre niveau d'étude</label>
                                    <select class="form-select search-input" id="niveauSelect" name="niveau_id" required>
                                        <option value="">Sélectionnez un niveau</option>
                                        @foreach(\App\Models\Niveau::orderBy('ordre')->get() as $niveau)
                                            <option value="{{ $niveau->id }}" data-niveau="{{ $niveau->nom }}">
                                                @if($niveau->nom == 'Je prépare mon bac')
                                                    🎯 Je prépare mon bac
                                                @elseif($niveau->nom == 'Bac')
                                                    🎓 Bac
                                                @else
                                                    {{ $niveau->nom }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted d-block mt-1">De la préparation du bac à la Licence 3</small>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label text-dark mb-2" style="font-weight: 600;">🎓 Choisissez la filière qui vous intéresse</label>
                                    <select class="form-select search-input" id="filiereSelect" name="filiere_id" required disabled>
                                        <option value="">Sélectionnez d'abord un niveau</option>
                                        @foreach(\App\Models\Filiere::all() as $filiere)
                                            <option value="{{ $filiere->id }}" data-filiere="{{ $filiere->nom }}">{{ $filiere->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn search-button w-100" id="searchButton" disabled>
                                        🔍 Voir les formations
                                    </button>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-container">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3" data-aos="fade-up">
                <div class="stat-card">
                    <div class="stat-number">{{ \App\Models\Filiere::count() }}+</div>
                    <div class="stat-label">Filières</div>
                </div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-card">
                    <div class="stat-number">{{ \App\Models\User::where('role', 'enseignant')->count() }}+</div>
                    <div class="stat-label">Enseignants</div>
                </div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-card">
                    <div class="stat-number">{{ \App\Models\User::where('role', 'etudiant')->count() }}K+</div>
                    <div class="stat-label">Étudiants</div>
                </div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-card">
                    <div class="stat-number">95%</div>
                    <div class="stat-label">Réussite</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="about-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="about-content">
                    <h2>À Propos de l'IESCA</h2>
                    <p>
                        L'Institut d'Enseignement Supérieur de la Côte Africaine (IESCA) est un établissement d'excellence 
                        situé au 112, Avenue de France (Poto poto), dédié à la formation de leaders et d'innovateurs.
                    </p>
                    <p>
                        Nous offrons des formations de qualité en Licence dans 4 domaines clés : Sciences et Administration 
                        des Affaires, Génie Informatique, Sciences Juridiques et Sciences Commerciales.
                    </p>
                    
                    <div class="about-features">
                        <div class="feature-item">
                            <div class="feature-icon">💻</div>
                            <div>
                                <h5 style="font-weight: 700; margin-bottom: 0.5rem;">Salle d'Informatique</h5>
                                <p style="color: var(--color-dark); margin: 0;">Équipements modernes et performants</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">📚</div>
                            <div>
                                <h5 style="font-weight: 700; margin-bottom: 0.5rem;">Bibliothèque</h5>
                                <p style="color: var(--color-dark); margin: 0;">Ressources académiques complètes</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">❄️</div>
                            <div>
                                <h5 style="font-weight: 700; margin-bottom: 0.5rem;">Classes Climatisées</h5>
                                <p style="color: var(--color-dark); margin: 0;">Confort optimal pour l'apprentissage</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">👨‍🏫</div>
                            <div>
                                <h5 style="font-weight: 700; margin-bottom: 0.5rem;">Formation Complète</h5>
                                <p style="color: var(--color-dark); margin: 0;">Cours théoriques et pratiques</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">📹</div>
                            <div>
                                <h5 style="font-weight: 700; margin-bottom: 0.5rem;">Caméras de Surveillance</h5>
                                <p style="color: var(--color-dark); margin: 0;">Sécurité assurée 24/7</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">🏢</div>
                            <div>
                                <h5 style="font-weight: 700; margin-bottom: 0.5rem;">Stage Garanti</h5>
                                <p style="color: var(--color-dark); margin: 0;">En fin de formation</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">💻</div>
                            <div>
                                <h5 style="font-weight: 700; margin-bottom: 0.5rem;">Ordinateur à Crédit</h5>
                                <p style="color: var(--color-dark); margin: 0;">Facilité de paiement disponible</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="about-image">
                    <img src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?w=800" alt="Campus IESCA">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Admission Timeline -->
<section class="admission-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title">Processus d'Admission</h2>
            <p class="section-subtitle">Quatre étapes simples pour rejoindre l'excellence</p>
        </div>
        
        <div class="timeline-container">
            <div class="timeline-item" data-aos="fade-right">
                <div class="timeline-number">1</div>
                <div class="timeline-content">
                    <h4 class="timeline-title">Inscription en Ligne</h4>
                    <p class="timeline-description">Créez votre compte et soumettez votre dossier de candidature en quelques clics.</p>
                </div>
            </div>
            
            <div class="timeline-item" data-aos="fade-right" data-aos-delay="100">
                <div class="timeline-number">2</div>
                <div class="timeline-content">
                    <h4 class="timeline-title">Vérification Administrative</h4>
                    <p class="timeline-description">Notre équipe examine votre dossier sous 48h.</p>
                </div>
            </div>
            
            <div class="timeline-item" data-aos="fade-right" data-aos-delay="200">
                <div class="timeline-number">3</div>
                <div class="timeline-content">
                    <h4 class="timeline-title">Évaluation du Comité</h4>
                    <p class="timeline-description">Le comité d'admission étudie votre profil académique.</p>
                </div>
            </div>
            
            <div class="timeline-item" data-aos="fade-right" data-aos-delay="300">
                <div class="timeline-number">4</div>
                <div class="timeline-content">
                    <h4 class="timeline-title">Décision d'Admission</h4>
                    <p class="timeline-description">Recevez votre décision par email.</p>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-5" data-aos="fade-up" data-aos-delay="400">
            <a href="{{ route('admission') }}" class="cta-button">
                Commencer Ma Candidature
            </a>
        </div>
    </div>
</section>

<!-- News Section -->
<section class="news-section">
    <div class="container">
        <div class="section-header text-dark" data-aos="fade-up">
            <h2 class="section-title" style="color: var(--color-black);">Actualités IESCA</h2>
            <p class="section-subtitle" style="color: var(--color-black); font-weight: 500;">Restez informé de nos dernières nouvelles</p>
        </div>
        
        <div class="row g-4">
            @php
                $actualites = \App\Models\Actualite::publie()->recent()->take(3)->get();
            @endphp
            
            @forelse($actualites as $actu)
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="news-card">
                        <div class="news-image">
                            <img src="{{ $actu->image ?? 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=800' }}" alt="{{ $actu->titre }}">
                            <div class="news-badge">{{ $actu->categorie }}</div>
                        </div>
                        <div class="news-content">
                            <div class="news-date">
                                📅 {{ $actu->date_publication->format('d M Y') }}
                            </div>
                            <h3 class="news-title">{{ $actu->titre }}</h3>
                            <p class="news-description">{{ Str::limit($actu->description, 120) }}</p>
                            <a href="#" class="read-more">
                                Lire la suite →
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center p-5">
                        <h4>Aucune actualité pour le moment</h4>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Events Section with Calendar -->
<section class="events-section">
    <div class="container">
        <div class="section-header text-dark" data-aos="fade-up">
            <h2 class="section-title" style="color: var(--color-black);">Calendrier des Événements</h2>
            <p class="section-subtitle" style="color: var(--color-dark);">Ne manquez aucun de nos événements</p>
        </div>
        
        <div class="row g-4">
            <!-- Events List -->
            <div class="col-lg-6" data-aos="fade-right">
                <div class="events-list">
                    @php
                        $evenements = \App\Models\Evenement::publie()->aVenir()->get();
                    @endphp
                    
                    @forelse($evenements as $event)
                        <div class="event-card">
                            <div class="event-date-badge">
                                {{ $event->date_debut->format('d M Y') }} - {{ $event->date_debut->format('H:i') }}
                            </div>
                            <h3 class="event-title">{{ $event->titre }}</h3>
                            <p class="mb-2">{{ $event->description }}</p>
                            <div class="event-info">
                                @if($event->lieu)
                                    <div>📍 {{ $event->lieu }}</div>
                                @endif
                                <div><span class="badge" style="background: var(--color-secondary);">{{ $event->type }}</span></div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center p-5">
                            <h4>Aucun événement programmé</h4>
                        </div>
                    @endforelse
                </div>
            </div>
            
            <!-- Calendar -->
            <div class="col-lg-6" data-aos="fade-left">
                <div class="calendar-container">
                    <div class="calendar-header">
                        <h3 class="calendar-title">{{ now()->format('F Y') }}</h3>
                        <div class="calendar-nav">
                            <button>‹</button>
                            <button>›</button>
                        </div>
                    </div>
                    
                    <div class="calendar-grid">
                        <!-- Day Headers -->
                        @foreach(['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'] as $day)
                            <div class="calendar-day-header">{{ $day }}</div>
                        @endforeach
                        
                        <!-- Calendar Days -->
                        @php
                            $startOfMonth = now()->startOfMonth();
                            $daysInMonth = now()->daysInMonth;
                            $startDay = $startOfMonth->dayOfWeek == 0 ? 7 : $startOfMonth->dayOfWeek;
                            $eventDays = \App\Models\Evenement::publie()
                                ->whereMonth('date_debut', now()->month)
                                ->get()
                                ->pluck('date_debut')
                                ->map(fn($date) => $date->day)
                                ->toArray();
                        @endphp
                        
                        @for($i = 1; $i < $startDay; $i++)
                            <div class="calendar-day"></div>
                        @endfor
                        
                        @for($day = 1; $day <= $daysInMonth; $day++)
                            <div class="calendar-day {{ in_array($day, $eventDays) ? 'has-event' : '' }} {{ $day == now()->day ? 'today' : '' }}">
                                {{ $day }}
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 cta-content" data-aos="fade-right">
                <h2 class="cta-title">Prêt à Rejoindre l'Excellence ?</h2>
                <p class="cta-subtitle">Les inscriptions sont ouvertes. Commencez votre parcours vers le succès.</p>
            </div>
            <div class="col-lg-4 text-end" data-aos="fade-left">
                <a href="{{ route('admission') }}" class="cta-button">
                    Postuler Maintenant
                </a>
            </div>
        </div>
    </div>
</section>

<!-- AOS Library -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 1000,
    once: true,
    offset: 100
  });

  // Gestion du formulaire de recherche
  document.addEventListener('DOMContentLoaded', function() {
    const niveauSelect = document.getElementById('niveauSelect');
    const filiereSelect = document.getElementById('filiereSelect');
    const searchButton = document.getElementById('searchButton');
    
    // Stocker toutes les filières
    const allFilieres = [
      @foreach(\App\Models\Filiere::all() as $filiere)
        {id: {{ $filiere->id }}, nom: "{{ $filiere->nom }}"},
      @endforeach
    ];
    
    // Quand un niveau est sélectionné, activer le select filière
    niveauSelect.addEventListener('change', function() {
      if (this.value) {
        filiereSelect.disabled = false;
        filiereSelect.innerHTML = '<option value="">Sélectionnez une filière</option>';
        allFilieres.forEach(function(filiere) {
          filiereSelect.innerHTML += '<option value="' + filiere.id + '">' + filiere.nom + '</option>';
        });
      } else {
        filiereSelect.disabled = true;
        filiereSelect.innerHTML = '<option value="">Sélectionnez d\'abord un niveau</option>';
        searchButton.disabled = true;
      }
    });
    
    // Quand une filière est sélectionnée, activer le bouton
    filiereSelect.addEventListener('change', function() {
      if (this.value && niveauSelect.value) {
        searchButton.disabled = false;
      } else {
        searchButton.disabled = true;
      }
    });
  });
</script>
@endsection
