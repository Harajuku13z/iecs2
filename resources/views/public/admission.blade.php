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
    border-radius: 8px;
    padding: 2rem;
    box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    margin-bottom: 2rem;
    border-left: 5px solid var(--color-primary);
}

.price-box {
    background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
    color: white;
    padding: 2rem;
    border-radius: 8px;
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
    border-radius: 8px;
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
        <h1>{{ \App\Models\Setting::get('admission_title', 'Procédure d\'Admission') }}</h1>
        <p class="lead">{{ \App\Models\Setting::get('admission_subtitle', 'Rejoignez l\'excellence académique - Année 2025-2026') }}</p>
        @if(\App\Models\Setting::get('admission_enable_pdf', '1') == '1')
        <div class="mt-4">
            <a href="{{ route('admission.download-pdf') }}" class="btn btn-light btn-lg" style="font-weight: 700; border-radius: 8px;">
                📥 Télécharger le dossier d'information (PDF)
            </a>
        </div>
        @endif
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <!-- Left Column - Info -->
        <div class="col-lg-8">
            <!-- Dates importantes -->
            <div class="info-card" data-aos="fade-up">
                <h3 class="mb-4" style="color: var(--color-black); font-weight: 700; font-size: 1.5rem;">📅 Dates Importantes</h3>
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
            @if(\App\Models\Setting::get('admission_enable_documents', '1') == '1')
            <div class="info-card" data-aos="fade-up" data-aos-delay="100">
                <h3 class="mb-4" style="color: var(--color-black); font-weight: 700; font-size: 1.5rem;">📋 Documents Requis</h3>
                <ul class="document-list">
                    @php
                        $documents = explode("\n", \App\Models\Setting::get('admission_documents', "Photocopie du Diplôme (BAC)\nPhotocopie en couleur de l'acte de naissance\nUne enveloppe kraft A4\n1 Paquet de marqueur tableau blanc (Permanent)"));
                    @endphp
                    @foreach($documents as $doc)
                        @if(trim($doc))
                            <li>{{ trim($doc) }}</li>
                        @endif
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Services disponibles -->
            @if(\App\Models\Setting::get('admission_enable_services', '1') == '1')
            <div class="info-card" data-aos="fade-up" data-aos-delay="200">
                <h3 class="mb-4" style="color: var(--color-black); font-weight: 700; font-size: 1.5rem;">📋 Services disponibles</h3>
                <div class="row g-2">
                    @php
                        $services = explode("\n", \App\Models\Setting::get('admission_services', "Inscription / Réinscription\nCoût de la Formation\nCalendrier académique 2024-2025 et Horaires de cours\nPublication des résultats des examens\nGuide de l'étudiant 2024-2025\nCarte de l'étudiant 2024-2025\nCursus de l'étudiant\nAttestation de fréquentation\nAttestation d'inscription\nAssiduité de l'étudiant\nRelevés des notes\nAssistance des étudiants\nDélivrance de diplôme"));
                    @endphp
                    @foreach($services as $service)
                        @if(trim($service))
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="me-2">✅</span>
                                    <span>{{ trim($service) }}</span>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Conditions d'inscription -->
            @if(\App\Models\Setting::get('admission_enable_conditions_l1', '1') == '1' || \App\Models\Setting::get('admission_enable_conditions_m1', '1') == '1')
            <div class="info-card" data-aos="fade-up" data-aos-delay="300">
                <h3 class="mb-3" style="color: var(--color-black); font-weight: 700; font-size: 1.5rem;">📚 Conditions d'inscription</h3>
                <div class="d-flex gap-2 mb-4">
                    @if(\App\Models\Setting::get('admission_enable_conditions_l1', '1') == '1')
                    <button type="button" class="btn btn-sm" id="btnShowPremier" style="background: var(--color-primary); color: #fff;">Premier cycle</button>
                    @endif
                    @if(\App\Models\Setting::get('admission_enable_conditions_m1', '1') == '1')
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="btnShowDeuxieme">Deuxième cycle</button>
                    @endif
                </div>
                
                @if(\App\Models\Setting::get('admission_enable_conditions_l1', '1') == '1')
                <div id="blocPremierCycle">
                <h5 class="mb-3" style="color: var(--color-primary); font-weight: 700;">Premier Cycle</h5>
                <div class="mb-4">
                    <h6 class="mb-2" style="font-weight: 600;">1ère année</h6>
                    <ul class="mb-3">
                        @php
                            $l1_conditions = explode("\n", \App\Models\Setting::get('admission_conditions_l1', "Être titulaire d'un Baccalauréat ou d'un diplôme équivalent."));
                        @endphp
                        @foreach($l1_conditions as $cond)
                            @if(trim($cond))
                                <li>✅ {{ trim($cond) }}</li>
                            @endif
                        @endforeach
                    </ul>
                    
                    <h6 class="mb-2" style="font-weight: 600;">2ème année</h6>
                    <ul class="mb-3">
                        @php
                            $l2_conditions = explode("\n", \App\Models\Setting::get('admission_conditions_l2', "Fournir un dossier scolaire de l'ESGAE\nOu avoir une équivalence de 60 CECT (Crédits d'évaluation capitalisables et transférables)."));
                        @endphp
                        @foreach($l2_conditions as $cond)
                            @if(trim($cond))
                                <li>✅ {{ trim($cond) }}</li>
                            @endif
                        @endforeach
                    </ul>
                    
                    <h6 class="mb-2" style="font-weight: 600;">3ème année</h6>
                    <ul class="mb-0">
                        @php
                            $l3_conditions = explode("\n", \App\Models\Setting::get('admission_conditions_l3', "Être titulaire d'un BTSE, BTS ou d'un diplôme équivalent avec 120 CECT."));
                        @endphp
                        @foreach($l3_conditions as $cond)
                            @if(trim($cond))
                                <li>✅ {{ trim($cond) }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                </div>
                @endif

                @if(\App\Models\Setting::get('admission_enable_conditions_m1', '1') == '1')
                <div id="blocDeuxiemeCycle" style="display:none;">
                <h5 class="mb-3" style="color: var(--color-primary); font-weight: 700;">Deuxième Cycle</h5>
                <div class="mb-4">
                    <h6 class="mb-2" style="font-weight: 600;">Master 1</h6>
                    <ul class="mb-3">
                        @php
                            $m1_conditions = explode("\n", \App\Models\Setting::get('admission_conditions_m1', "Les titulaires d'une Licence (Bac+3) en gestion, économie, droit, médecine… ou d'une équivalence ECTS de 180 CECT."));
                        @endphp
                        @foreach($m1_conditions as $cond)
                            @if(trim($cond))
                                <li>✅ {{ trim($cond) }}</li>
                            @endif
                        @endforeach
                    </ul>
                    
                    <h6 class="mb-2" style="font-weight: 600;">Formation continue</h6>
                    <ul class="mb-3">
                        @php
                            $fc_conditions = explode("\n", \App\Models\Setting::get('admission_conditions_fc', "Être titulaire d'une Licence, d'un diplôme équivalent ou d'une équivalence ECTS de 180 CECT\nLa durée Formation continue est de 1 année 📅"));
                        @endphp
                        @foreach($fc_conditions as $cond)
                            @if(trim($cond))
                                <li>✅ {{ trim($cond) }}</li>
                            @endif
                        @endforeach
                    </ul>
                    
                    <h6 class="mb-2" style="font-weight: 600;">Master 2</h6>
                    <ul class="mb-0">
                        @php
                            $m2_conditions = explode("\n", \App\Models\Setting::get('admission_conditions_m2', "Les titulaires du diplôme d'ingénieur de l'ESGAE, du Master 1 de l'ESGAE ou du Certificat d'Etudes Supérieures en Administration des Entreprises CESAE de l'ESGAE"));
                        @endphp
                        @foreach($m2_conditions as $cond)
                            @if(trim($cond))
                                <li>✅ {{ trim($cond) }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                </div>
                @endif
            </div>
            @endif

            <!-- Dossier à fournir -->
            @if(\App\Models\Setting::get('admission_enable_dossier', '1') == '1')
            <div class="info-card" data-aos="fade-up" data-aos-delay="400">
                <h3 class="mb-4" style="color: var(--color-black); font-weight: 700; font-size: 1.5rem;">📄 Dossier à fournir</h3>
                
                <h5 class="mb-3" style="color: var(--color-primary); font-weight: 700;">Premier cycle</h5>
                <ul class="mb-4">
                    @php
                        $dossier_l1 = explode("\n", \App\Models\Setting::get('admission_dossier_l1', "1 extrait d'acte de naissance (original ou copie certifiée conforme)\n2 photos d'identité couleur sur fond blanc\n1 copie légalisée du baccalauréat"));
                    @endphp
                    @foreach($dossier_l1 as $doc)
                        @if(trim($doc))
                            <li class="mb-2">• {{ trim($doc) }}</li>
                        @endif
                    @endforeach
                </ul>

                <h5 class="mb-3" style="color: var(--color-primary); font-weight: 700;">Deuxième cycle</h5>
                <ul class="mb-0">
                    @php
                        $dossier_m1 = explode("\n", \App\Models\Setting::get('admission_dossier_m1', "1 copie légalisée de la licence ou d'un diplôme équivalent"));
                    @endphp
                    @foreach($dossier_m1 as $doc)
                        @if(trim($doc))
                            <li class="mb-2">• {{ trim($doc) }}</li>
                        @endif
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Filières disponibles -->
            <div class="info-card" data-aos="fade-up" data-aos-delay="500">
                <h3 class="mb-4" style="color: var(--color-black); font-weight: 700; font-size: 1.5rem;">🎓 Nos Filières en Licence</h3>
                
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
            <div class="info-card" data-aos="fade-up" data-aos-delay="600">
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
                        <div class="alert alert-info">
                            Votre candidature sera associée à votre compte : <strong>{{ auth()->user()->email }}</strong>
                        </div>
                        <a href="{{ route('candidature.create') }}" class="btn btn-lg w-100" style="background: var(--color-primary); color: white;">
                            Soumettre ma candidature
                        </a>
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
            @if(\App\Models\Setting::get('admission_enable_frais', '1') == '1')
            <div class="price-box" data-aos="fade-left">
                <h5 class="mb-0">Frais d'Inscription</h5>
                <div class="price">{{ number_format(\App\Models\Setting::get('admission_frais_inscription', \App\Models\Setting::get('frais_inscription', 30000)), 0, ',', ' ') }} FCFA</div>
                <div style="background: rgba(255,255,255,0.2); padding: 1rem; border-radius: 8px; margin-top: 1rem;">
                    @php
                        $fraisBonus = explode("\n", \App\Models\Setting::get('admission_frais_bonus', "Carte d'étudiant : Gratuite\nTote : Gratuite\nAssurance : Gratuite"));
                    @endphp
                    @foreach($fraisBonus as $bonus)
                        @if(trim($bonus))
                            <small>✅ {{ trim($bonus) }}</small><br>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Frais mensuels -->
            <div class="info-card mt-4" data-aos="fade-left" data-aos-delay="100">
                <h5 style="color: var(--color-black); font-weight: 700; font-size: 1.2rem;">💳 Scolarité Mensuelle</h5>
                <div class="text-center py-3">
                    <div class="h2" style="color: var(--color-primary); font-weight: 900;">
                        {{ number_format(\App\Models\Setting::get('admission_frais_mensuels', \App\Models\Setting::get('frais_mensuels', 35000)), 0, ',', ' ') }} FCFA
                    </div>
                    <small class="text-muted">par mois</small>
                </div>
            </div>
            @endif

            <!-- Avantage spécial -->
            @if(\App\Models\Setting::get('admission_enable_avantage', '1') == '1')
            <div class="info-card" data-aos="fade-left" data-aos-delay="200">
                <h5 style="color: var(--color-black); font-weight: 700; font-size: 1.2rem;">💻 Avantage Spécial</h5>
                <p class="mb-0">{!! nl2br(e(\App\Models\Setting::get('admission_avantage', "Possibilité d'achat d'un ordinateur à crédit pour faciliter vos études."))) !!}</p>
            </div>
            @endif

            <!-- Contact rapide -->
            @if(\App\Models\Setting::get('admission_enable_contact', '1') == '1')
            <div class="info-card" data-aos="fade-left" data-aos-delay="300">
                <h5 style="color: var(--color-black); font-weight: 700; font-size: 1.2rem;">{{ \App\Models\Setting::get('admission_contact_title', '📞 Besoin d\'Aide ?') }}</h5>
                <p class="mb-2">{{ \App\Models\Setting::get('admission_contact_text', 'Contactez-nous :') }}</p>
                <p class="mb-1"><strong>{{ \App\Models\Setting::get('phone1', '') }}</strong></p>
                <p class="mb-0"><strong>{{ \App\Models\Setting::get('phone2', '') }}</strong></p>
            </div>
            @endif
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
  // Toggle Premier / Deuxième cycle
  const btnPremier = document.getElementById('btnShowPremier');
  const btnDeuxieme = document.getElementById('btnShowDeuxieme');
  const blocPremier = document.getElementById('blocPremierCycle');
  const blocDeuxieme = document.getElementById('blocDeuxiemeCycle');

  if (btnPremier && btnDeuxieme && blocPremier && blocDeuxieme) {
    btnPremier.addEventListener('click', () => {
      blocPremier.style.display = '';
      blocDeuxieme.style.display = 'none';
      btnPremier.classList.remove('btn-outline-secondary');
      btnPremier.style.background = getComputedStyle(document.documentElement).getPropertyValue('--color-primary');
      btnPremier.style.color = '#fff';
      btnDeuxieme.classList.add('btn-outline-secondary');
      btnDeuxieme.style.background = '';
      btnDeuxieme.style.color = '';
    });
    btnDeuxieme.addEventListener('click', () => {
      blocPremier.style.display = 'none';
      blocDeuxieme.style.display = '';
      btnDeuxieme.classList.remove('btn-outline-secondary');
      btnDeuxieme.style.background = getComputedStyle(document.documentElement).getPropertyValue('--color-primary');
      btnDeuxieme.style.color = '#fff';
      btnPremier.classList.add('btn-outline-secondary');
      btnPremier.style.background = '';
      btnPremier.style.color = '';
    });
  }
</script>
@endsection
