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

.formations-hero h1 { font-size: 2.2rem; font-weight: 800; margin-bottom: .75rem; }

.formation-result-card {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    transition: all 0.4s ease;
    margin-bottom: 2rem;
    border: 1px solid rgba(0,0,0,0.06);
}

.formation-result-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 60px rgba(166, 96, 96, 0.15);
}

.formation-header {
    background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
    color: white;
    padding: 2rem;
    text-align: center;
}

.formation-title { font-size: 1.6rem; font-weight: 800; margin: 0 0 .5rem 0; }

.formation-subtitle {
    font-size: 1.2rem;
    opacity: 0.9;
}

.formation-body { padding: 2rem; }

.formation-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.info-box {
    background: var(--color-light);
    padding: 1.5rem;
    border-radius: 8px;
    border-left: 4px solid var(--color-primary);
}

.info-box h5 {
    color: var(--color-primary);
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.classes-list {
    list-style: none;
    padding: 0;
}

.classes-list li {
    padding: 1rem;
    margin-bottom: 0.75rem;
    background: var(--color-light);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: all 0.3s ease;
}

.classes-list li:hover {
    background: var(--color-primary);
    color: white;
    transform: translateX(10px);
}

.filiere-card {
    background: white;
    border-radius: 8px;
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

.filiere-title { font-size: 1.4rem; font-weight: 800; margin: 0; }

.filiere-body { padding: 1.5rem; }

.program-list {
    list-style: none;
    padding: 0;
}

.program-list li {
    padding: 1rem;
    margin-bottom: 0.75rem;
    background: var(--color-light);
    border-radius: 8px;
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

.apply-btn { background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); color:#fff; border:none; padding:.9rem 1.5rem; border-radius: 6px; font-weight:700; transition:all .3s ease; text-decoration:none; display:inline-block; }

.apply-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(166, 96, 96, 0.3);
    color: white;
}

.search-again { background:white; border:1px solid var(--color-primary); color:var(--color-primary); padding:.9rem 1.5rem; border-radius: 6px; font-weight:700; transition:all .3s ease; text-decoration:none; display:inline-block; }

.search-again:hover {
    background: var(--color-primary);
    color: white;
}
</style>

<div class="formations-hero">
    <div class="container">
        @if(isset($niveau) && isset($filiere))
            <h1>Formations Adaptées Pour Vous</h1>
            <p class="lead">{{ $filiere->nom }} - {{ $niveau->nom }}</p>
        @else
            <h1>Nos Formations en Licence</h1>
            <p class="lead">Année Académique 2025-2026</p>
        @endif
    </div>
</div>

<div class="container py-5">
    <!-- Bloc recherche (style similaire à la home) -->
    <div class="premium-search mb-4" data-aos="fade-up">
        <h4 class="text-center text-dark mb-2" style="font-weight: 700; font-size: 1.1rem;">Trouvez Votre Formation Idéale</h4>
        <form action="{{ route('formations') }}" method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label text-dark mb-1" style="font-weight: 600; font-size: 0.85rem;">📚 Niveau d'étude</label>
                    <select class="form-select search-input" name="niveau_id" required style="height: 45px; font-size: 0.9rem;">
                        <option value="">Sélectionnez un niveau</option>
                        @foreach(\App\Models\Niveau::orderBy('ordre')->get() as $niv)
                            <option value="{{ $niv->id }}" @selected(request('niveau_id')==$niv->id)>{{ $niv->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label text-dark mb-1" style="font-weight: 600; font-size: 0.85rem;">🎓 Filière</label>
                    <select class="form-select search-input" name="filiere_id" required style="height: 45px; font-size: 0.9rem;">
                        <option value="">Sélectionnez une filière</option>
                        @foreach(\App\Models\Filiere::orderBy('nom')->get() as $fil)
                            <option value="{{ $fil->id }}" @selected(request('filiere_id')==$fil->id)>{{ $fil->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn search-button w-100" style="height: 45px; font-size: 0.95rem;">
                        🔍 Rechercher
                    </button>
                </div>
            </div>
        </form>
    </div>
    @if(isset($niveau) && isset($filiere))
        <!-- Résultat de recherche -->
        <div class="row">
            <div class="col-12" data-aos="fade-up">
                <div class="formation-result-card">
                    <div class="formation-header">
                        <h2 class="formation-title">{{ $filiere->nom }}</h2>
                        <p class="formation-subtitle">Niveau : {{ $niveau->nom }}</p>
                    </div>
                    <div class="formation-body">
                        <div class="formation-info">
                            <div class="info-box">
                                <h5>📚 Filière</h5>
                                <p class="mb-0" style="font-size: 1.1rem; font-weight: 600;">{{ $filiere->nom }}</p>
                            </div>
                            <div class="info-box">
                                <h5>📈 Niveau</h5>
                                <p class="mb-0" style="font-size: 1.1rem; font-weight: 600;">{{ $niveau->nom }}</p>
                            </div>
                            <div class="info-box">
                                <h5>🏫 Classes Disponibles</h5>
                                <p class="mb-0" style="font-size: 1.1rem; font-weight: 600;">{{ $classes->count() }} classe(s)</p>
                            </div>
                        </div>
                        
                        @if(isset($suggestL1) && $suggestL1)
                            <div class="alert alert-warning border border-warning">
                                <h5 class="mb-3">💡 Information importante</h5>
                                <p class="mb-3">Pour intégrer l'IESCA, vous devez avoir le Baccalauréat. Si vous êtes en préparation du bac ou bachelier, vous pouvez postuler pour la <strong>L1 (Licence 1)</strong>.</p>
                                
                                @if($suggestedClasses->count() > 0)
                                    <h5 class="mt-4 mb-3" style="color: var(--color-black); font-weight: 700;">Formations L1 disponibles pour cette filière</h5>
                                    <ul class="classes-list">
                                        @foreach($suggestedClasses as $classe)
                                            <li>
                                                <div>
                                                    <strong style="font-size: 1.1rem;">{{ $classe->nom }}</strong>
                                                    <small class="d-block text-muted">{{ $classe->filiere->nom }} - {{ $classe->niveau->nom }}</small>
                                                </div>
                                                <a href="{{ route('admission') }}" class="btn btn-sm" style="background: var(--color-primary); color: white;">
                                                    Postuler →
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        @elseif($classes->count() > 0)
                            <h4 class="mb-3" style="color: var(--color-black); font-weight: 700;">Classes Disponibles</h4>
                            <ul class="classes-list">
                                @foreach($classes as $classe)
                                    <li>
                                        <div>
                                            <strong style="font-size: 1.1rem;">{{ $classe->nom }}</strong>
                                            <small class="d-block text-muted">{{ $classe->filiere->nom }} - {{ $classe->niveau->nom }}</small>
                                        </div>
                                        <a href="{{ route('admission') }}" class="btn btn-sm" style="background: var(--color-primary); color: white;">
                                            Postuler →
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="alert alert-info text-center">
                                <h5>Aucune classe disponible</h5>
                                <p>Il n'y a pas encore de classe pour cette combinaison niveau/filière.</p>
                            </div>
                        @endif
                        
                        <div class="mt-4 text-center">
                            <a href="{{ route('admission') }}" class="apply-btn me-3">
                                Postuler Maintenant
                            </a>
                            <a href="{{ route('formations') }}" class="search-again">
                                🔄 Nouvelle Recherche
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Liste dynamique des filières en cartes (image + description + spécialités) -->
        <div class="row g-4">
            @foreach(\App\Models\Filiere::with('specialites')->orderBy('nom')->get() as $f)
                <div class="col-12" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                    <div class="card border-0 shadow-sm" style="border-radius: 8px; overflow: hidden;">
                        <div class="row g-0 align-items-stretch">
                            <div class="col-md-4" style="background:#f7f7f7;">
                                @if($f->image)
                                    <img src="{{ asset('storage/' . $f->image) }}" alt="{{ $f->nom }}" style="width:100%; height:100%; object-fit:cover; display:block;">
                                @else
                                    <div class="d-flex align-items-center justify-content-center" style="width:100%; height:100%; min-height:220px; font-size:3rem;">🎓</div>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <div class="p-3 p-md-4 h-100 d-flex flex-column">
                                    <h3 class="mb-2" style="font-weight:800; font-size:1.25rem;">{{ $f->nom }}</h3>
                                    <p class="text-muted" style="margin-bottom: .75rem;">{{ \Illuminate\Support\Str::limit($f->description ?? 'Formation d\'excellence', 220) }}</p>
                                    @if($f->specialites && $f->specialites->count())
                                        <div class="d-flex flex-wrap gap-1 mb-3">
                                            @foreach($f->specialites as $sp)
                                                <span class="badge bg-light text-dark border">{{ $sp->nom }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                    <div class="mt-auto d-flex gap-2">
                                        <a href="{{ route('formations.show', $f) }}" class="btn btn-site">Voir la formation</a>
                                        <a href="{{ route('admission') }}" class="btn btn-outline-secondary">Soumettre ma candidature</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
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
