@extends('layouts.app')

@section('title', 'Nos Formations - IESCA')

@section('content')
<style>
.formations-hero {
    background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-black) 100%);
    color: white;
    padding: 5rem 0;
    text-align: center;
}

.formations-hero h1 {
    font-size: 3.5rem;
    font-weight: 900;
    margin-bottom: 1rem;
}

.filiere-card {
    background: white;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    transition: all 0.4s ease;
    margin-bottom: 2rem;
    border: 1px solid rgba(166, 96, 96, 0.1);
}

.filiere-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 60px rgba(166, 96, 96, 0.15);
}

.filiere-header {
    background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
    color: white;
    padding: 2rem;
}

.filiere-title {
    font-size: 2rem;
    font-weight: 900;
    margin: 0;
}

.filiere-body {
    padding: 2rem;
}

.program-list {
    list-style: none;
    padding: 0;
}

.program-list li {
    padding: 1rem;
    margin-bottom: 0.75rem;
    background: var(--color-light);
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
}

.program-list li:hover {
    background: var(--color-primary);
    color: white;
    transform: translateX(10px);
}

.program-list li::before {
    content: '🎓';
    font-size: 1.5rem;
}

.apply-btn {
    background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
    color: white;
    border: none;
    padding: 1rem 2rem;
    border-radius: 50px;
    font-weight: 700;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
}

.apply-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(166, 96, 96, 0.3);
    color: white;
}
</style>

<div class="formations-hero">
    <div class="container">
        <h1>Nos Formations en Licence</h1>
        <p class="lead">Année Académique 2025-2026</p>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <!-- Sciences et Administration des Affaires -->
        <div class="col-12" data-aos="fade-up">
            <div class="filiere-card">
                <div class="filiere-header">
                    <h2 class="filiere-title">Sciences et Administration des Affaires</h2>
                </div>
                <div class="filiere-body">
                    <h5 class="mb-3" style="color: var(--color-dark);">Programmes disponibles :</h5>
                    <ul class="program-list">
                        <li><strong>Management et Entrepreneuriat</strong> - Former les futurs managers et entrepreneurs</li>
                        <li><strong>Gestion des Ressources Humaines</strong> - Maîtriser la gestion du capital humain</li>
                    </ul>
                    <div class="mt-4">
                        <a href="{{ route('admission') }}" class="apply-btn">Postuler pour cette filière →</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Génie Informatique -->
        <div class="col-12" data-aos="fade-up" data-aos-delay="100">
            <div class="filiere-card">
                <div class="filiere-header">
                    <h2 class="filiere-title">Génie Informatique</h2>
                </div>
                <div class="filiere-body">
                    <h5 class="mb-3" style="color: var(--color-dark);">Programmes disponibles :</h5>
                    <ul class="program-list">
                        <li><strong>Réseaux et Télécommunications</strong> - Architecture et sécurité des réseaux</li>
                        <li><strong>Informatique de Gestion</strong> - Systèmes d'information et développement</li>
                    </ul>
                    <div class="mt-4">
                        <a href="{{ route('admission') }}" class="apply-btn">Postuler pour cette filière →</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sciences Juridiques -->
        <div class="col-12" data-aos="fade-up" data-aos-delay="200">
            <div class="filiere-card">
                <div class="filiere-header">
                    <h2 class="filiere-title">Sciences Juridiques</h2>
                </div>
                <div class="filiere-body">
                    <h5 class="mb-3" style="color: var(--color-dark);">Programmes disponibles :</h5>
                    <ul class="program-list">
                        <li><strong>Droit Privé</strong> - Maîtriser le droit civil et commercial</li>
                        <li><strong>Droit Public</strong> - Comprendre le droit administratif et constitutionnel</li>
                        <li><strong>Droit des Affaires</strong> - Expertise en droit commercial et des sociétés</li>
                    </ul>
                    <div class="mt-4">
                        <a href="{{ route('admission') }}" class="apply-btn">Postuler pour cette filière →</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sciences Commerciales -->
        <div class="col-12" data-aos="fade-up" data-aos-delay="300">
            <div class="filiere-card">
                <div class="filiere-header">
                    <h2 class="filiere-title">Sciences Commerciales</h2>
                </div>
                <div class="filiere-body">
                    <h5 class="mb-3" style="color: var(--color-dark);">Programmes disponibles :</h5>
                    <ul class="program-list">
                        <li><strong>Comptabilité</strong> - Expert-comptable et audit</li>
                        <li><strong>Management de la Chaîne Logistique</strong> - Supply chain et logistique</li>
                        <li><strong>Banque, Assurance et Finances</strong> - Finance d'entreprise et marché</li>
                    </ul>
                    <div class="mt-4">
                        <a href="{{ route('admission') }}" class="apply-btn">Postuler pour cette filière →</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- AOS Library -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 1000,
    once: true
  });
</script>
@endsection
