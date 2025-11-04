@extends('layouts.app')

@section('title', 'Admission - IESCA')

@section('content')
<style>
.admission-hero {
    background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-black) 100%);
    color: white;
    padding: 4rem 0;
    text-align: center;
}

.admission-hero h1 {
    font-size: 3.5rem;
    font-weight: 900;
    margin-bottom: 1rem;
}

.info-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    margin-bottom: 2rem;
    border-left: 5px solid var(--color-primary);
}

.price-box {
    background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
    color: white;
    padding: 2rem;
    border-radius: 20px;
    text-align: center;
}

.price {
    font-size: 3rem;
    font-weight: 900;
    margin: 1rem 0;
}

.document-list {
    list-style: none;
    padding: 0;
}

.document-list li {
    padding: 1rem;
    margin-bottom: 0.5rem;
    background: var(--color-light);
    border-radius: 10px;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.document-list li::before {
    content: '✓';
    background: var(--color-primary);
    color: white;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-weight: bold;
}
</style>

<div class="admission-hero">
    <div class="container">
        <h1>Procédure d'Admission</h1>
        <p class="lead">Rejoignez l'excellence académique - Année 2025-2026</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <!-- Left Column - Info -->
        <div class="col-lg-8">
            <!-- Dates importantes -->
            <div class="info-card" data-aos="fade-up">
                <h3 class="mb-4" style="color: var(--color-black); font-weight: 700;">📅 Dates Importantes</h3>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded">
                            <h5 style="color: var(--color-primary); font-weight: 700;">Début des Inscriptions</h5>
                            <p class="h4 mb-0">{{ \Carbon\Carbon::parse(\App\Models\Setting::get('inscription_start_date'))->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded">
                            <h5 style="color: var(--color-primary); font-weight: 700;">Début des Cours</h5>
                            <p class="h4 mb-0">{{ \Carbon\Carbon::parse(\App\Models\Setting::get('debut_cours'))->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents requis -->
            <div class="info-card" data-aos="fade-up" data-aos-delay="100">
                <h3 class="mb-4" style="color: var(--color-black); font-weight: 700;">📋 Documents Requis</h3>
                <ul class="document-list">
                    <li>Photocopie du Diplôme (BAC)</li>
                    <li>Photocopie en couleur de l'acte de naissance</li>
                    <li>Une enveloppe kraft A4</li>
                    <li>1 Paquet de marqueur tableau blanc (Permanent)</li>
                </ul>
            </div>

            <!-- Filières disponibles -->
            <div class="info-card" data-aos="fade-up" data-aos-delay="200">
                <h3 class="mb-4" style="color: var(--color-black); font-weight: 700;">🎓 Nos Filières en Licence</h3>
                
                @foreach(\App\Models\Filiere::all() as $filiere)
                    <div class="mb-3">
                        <h5 style="color: var(--color-primary); font-weight: 700;">{{ $filiere->nom }}</h5>
                        <p class="text-muted mb-0">{{ $filiere->description }}</p>
                    </div>
                    @if(!$loop->last)
                        <hr>
                    @endif
                @endforeach
            </div>

            <!-- Formulaire de candidature -->
            <div class="info-card" data-aos="fade-up" data-aos-delay="300">
                <h3 class="mb-4" style="color: var(--color-black); font-weight: 700;">📝 Soumettre ma Candidature</h3>
                
                @if(auth()->check())
                    @if(auth()->user()->isCandidat() && auth()->user()->candidature)
                        <div class="alert" style="background: var(--color-light); border-left: 5px solid var(--color-primary);">
                            <h5 style="color: var(--color-primary);">✅ Candidature déjà soumise</h5>
                            <p>Statut actuel: <strong>{{ auth()->user()->candidature->statut }}</strong></p>
                            <a href="{{ route('etudiant.dashboard') }}" class="btn" style="background: var(--color-primary); color: white;">
                                Voir le statut
                            </a>
                        </div>
                    @else
                        <form action="{{ route('admission.submit') }}" method="POST">
                            @csrf
                            <div class="alert alert-info">
                                Votre candidature sera associée à votre compte : <strong>{{ auth()->user()->email }}</strong>
                            </div>
                            <button type="submit" class="btn btn-lg w-100" style="background: var(--color-primary); color: white;">
                                Soumettre ma candidature
                            </button>
                        </form>
                    @endif
                @else
                    <form action="{{ route('candidature.start') }}" method="POST">
                        @csrf
                        <div class="row g-2 align-items-end">
                            <div class="col-md-5">
                                <label class="form-label">Nom complet</label>
                                <input type="text" name="name" class="form-control" placeholder="Votre nom et prénom" required>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="vous@example.com" required>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-lg w-100" style="background: var(--color-primary); color: white;">Soumettre</button>
                            </div>
                        </div>
                        <small class="text-muted">Un compte sera créé automatiquement et vous serez redirigé vers le dépôt des pièces.</small>
                    </form>
                @endif
            </div>
        </div>

        <!-- Right Column - Pricing & Info -->
        <div class="col-lg-4">
            <!-- Frais d'inscription -->
            <div class="price-box" data-aos="fade-left">
                <h5 class="mb-0">Frais d'Inscription</h5>
                <div class="price">{{ number_format(\App\Models\Setting::get('frais_inscription', 30000), 0, ',', ' ') }} FCFA</div>
                <div style="background: rgba(255,255,255,0.2); padding: 1rem; border-radius: 10px; margin-top: 1rem;">
                    <small>✅ Carte d'étudiant : <strong>Gratuite</strong></small><br>
                    <small>✅ Tote : <strong>Gratuite</strong></small><br>
                    <small>✅ Assurance : <strong>Gratuite</strong></small>
                </div>
            </div>

            <!-- Frais mensuels -->
            <div class="info-card mt-4" data-aos="fade-left" data-aos-delay="100">
                <h5 style="color: var(--color-black); font-weight: 700;">💳 Scolarité Mensuelle</h5>
                <div class="text-center py-3">
                    <div class="h2" style="color: var(--color-primary); font-weight: 900;">
                        {{ number_format(\App\Models\Setting::get('frais_mensuels', 30000), 0, ',', ' ') }} FCFA
                    </div>
                    <small class="text-muted">par mois</small>
                </div>
            </div>

            <!-- Avantage spécial -->
            <div class="info-card" data-aos="fade-left" data-aos-delay="200">
                <h5 style="color: var(--color-black); font-weight: 700;">💻 Avantage Spécial</h5>
                <p class="mb-0">Possibilité d'achat d'un <strong>ordinateur à crédit</strong> pour faciliter vos études.</p>
            </div>

            <!-- Contact rapide -->
            <div class="info-card" data-aos="fade-left" data-aos-delay="300">
                <h5 style="color: var(--color-black); font-weight: 700;">📞 Besoin d'Aide ?</h5>
                <p class="mb-2">Contactez-nous :</p>
                <p class="mb-1"><strong>{{ \App\Models\Setting::get('phone1') }}</strong></p>
                <p class="mb-0"><strong>{{ \App\Models\Setting::get('phone2') }}</strong></p>
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
