@extends('layouts.app')

@section('title', 'Actualités - IESCA')

@section('content')
<style>
.actualites-hero {
    position: relative;
    min-height: 50vh;
    color: white;
    padding: 3rem 0;
    text-align: center;
    display: flex;
    align-items: center;
    overflow: hidden;
}

@php
    $heroImage = \App\Models\Setting::get('actualites_hero_image', '');
    $heroImageUrl = $heroImage ? asset('storage/' . $heroImage) : '';
@endphp

.actualites-hero {
    @if($heroImageUrl)
    background-image: url('{{ $heroImageUrl }}');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    @else
    background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
    @endif
}

.actualites-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(166, 96, 96, 0.5) 0%, rgba(13, 13, 13, 0.5) 100%);
    z-index: 0;
}

.actualites-hero-content {
    position: relative;
    z-index: 1;
}

.actualites-hero h1 {
    font-size: 2.5rem;
    font-weight: 900;
    margin-bottom: 1rem;
}

.actualites-sidebar {
    background: var(--color-light);
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    position: sticky;
    top: 100px;
}

.sidebar-section {
    margin-bottom: 2rem;
}

.sidebar-section:last-child {
    margin-bottom: 0;
}

.sidebar-section h5 {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--color-black);
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--color-primary);
}

.categorie-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.categorie-list li {
    margin-bottom: 0.5rem;
}

.categorie-link {
    display: block;
    padding: 0.75rem 1rem;
    color: var(--color-black);
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.3s ease;
    font-weight: 500;
    border-left: 3px solid transparent;
}

.categorie-link:hover,
.categorie-link.active {
    background: white;
    color: var(--color-primary);
    border-left-color: var(--color-primary);
    transform: translateX(5px);
}

.categorie-link .count {
    float: right;
    color: var(--color-primary);
    font-weight: 600;
}

.actualite-card {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.actualite-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.15);
}

.actualite-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
}

.actualite-body {
    padding: 1.5rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.actualite-date {
    color: var(--color-primary);
    font-weight: 700;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.actualite-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--color-black);
    margin-bottom: 0.75rem;
    line-height: 1.3;
}

.actualite-description {
    color: #666;
    font-size: 0.95rem;
    margin-bottom: 1rem;
    flex: 1;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.actualite-categorie {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: var(--color-light);
    color: var(--color-primary);
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.section-title {
    font-size: 2rem;
    font-weight: 800;
    color: var(--color-black);
    margin-bottom: 2rem;
    text-align: left;
}

.recent-actualites-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.recent-actualites-list li {
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e0e0e0;
}

.recent-actualites-list li:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.recent-actualite-link {
    display: block;
    text-decoration: none;
    color: var(--color-black);
    transition: color 0.3s ease;
}

.recent-actualite-link:hover {
    color: var(--color-primary);
}

.recent-actualite-link .title {
    font-weight: 600;
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
    line-height: 1.3;
}

.recent-actualite-link .date {
    font-size: 0.75rem;
    color: #888;
}

.info-box {
    background: white;
    padding: 1rem;
    border-radius: 8px;
    border-left: 4px solid var(--color-primary);
}

.info-box p {
    margin: 0;
    font-size: 0.9rem;
    color: #666;
}

/* Pagination Styles */
.pagination {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin: 2rem 0;
}

.pagination .page-link {
    padding: 0.5rem 1rem;
    background: white;
    border: 1px solid var(--color-primary);
    color: var(--color-primary);
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.pagination .page-link:hover {
    background: var(--color-primary);
    color: white;
}

.pagination .page-item.active .page-link {
    background: var(--color-primary);
    color: white;
    border-color: var(--color-primary);
}

.pagination .page-item.disabled .page-link {
    opacity: 0.5;
    cursor: not-allowed;
}

@media (max-width: 768px) {
    .actualites-sidebar {
        position: relative;
        top: 0;
    }
}
</style>

@php
    $heroTitle = \App\Models\Setting::get('actualites_hero_title', '📰 Actualités IESCA');
    $heroSubtitle = \App\Models\Setting::get('actualites_hero_subtitle', 'Restez informé des dernières nouvelles de l\'IESCA');
    
    // Récupérer toutes les catégories avec comptage
    $allCategories = \App\Models\Actualite::where('publie', true)
        ->select('categorie', \DB::raw('count(*) as count'))
        ->groupBy('categorie')
        ->orderBy('count', 'desc')
        ->get();
    
    // Récupérer les actualités récentes pour la sidebar
    $recentActualites = \App\Models\Actualite::where('publie', true)
        ->orderBy('date_publication', 'desc')
        ->take(5)
        ->get();
    
    // Mettre en avant la première actualité
    $premiereActualite = $showAll ? null : ($actualites instanceof \Illuminate\Pagination\LengthAwarePaginator ? $actualites->first() : ($actualites->count() > 0 ? $actualites->first() : null));
    $autresActualites = $showAll ? $actualites : ($actualites instanceof \Illuminate\Pagination\LengthAwarePaginator ? $actualites->skip(1) : ($actualites->count() > 1 ? $actualites->skip(1) : collect()));
    
    $selectedCategorie = request('categorie', 'all');
@endphp

<div class="actualites-hero">
    <div class="container">
        <div class="actualites-hero-content">
            <h1>{{ $heroTitle }}</h1>
            <p class="lead">{{ $heroSubtitle }}</p>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <!-- Contenu principal -->
        <div class="col-lg-9 mb-4">
            <!-- Première actualité mise en avant (si pas en mode "voir tout" et qu'une catégorie n'est pas sélectionnée) -->
            @if(!$showAll && $premiereActualite && $selectedCategorie == 'all')
            <div class="mb-5">
                <div class="actualite-card" style="flex-direction: row; max-height: 400px;">
                    @if($premiereActualite->image)
                        <div style="flex: 0 0 40%; max-width: 40%;">
                            <img src="{{ asset('storage/' . $premiereActualite->image) }}" alt="{{ $premiereActualite->titre }}" 
                                 class="actualite-image" style="height: 100%; width: 100%; object-fit: cover; border-radius: 8px 0 0 8px;">
                        </div>
                    @endif
                    <div class="actualite-body" style="flex: 1;">
                        <div class="actualite-date">{{ optional($premiereActualite->date_publication)->format('d M Y') }}</div>
                        <h2 class="actualite-title">{{ $premiereActualite->titre }}</h2>
                        <p class="actualite-description" style="-webkit-line-clamp: 4;">{{ $premiereActualite->description }}</p>
                        @if($premiereActualite->categorie)
                            <span class="actualite-categorie">{{ $premiereActualite->categorie }}</span>
                        @endif
                        <a href="{{ route('actualites.show', $premiereActualite) }}" class="btn btn-sm mt-auto" style="background: var(--color-primary); color: white; border-radius: 8px; width: fit-content;">
                            Lire la suite →
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Liste des actualités -->
            @if($autresActualites->count() > 0 || ($showAll && $actualites->count() > 0))
            @if($selectedCategorie != 'all' || !$showAll)
                <h2 class="section-title">
                    @if($selectedCategorie != 'all')
                        Actualités : {{ $selectedCategorie }}
                    @else
                        @if(!$showAll && $premiereActualite)
                            Autres Actualités
                        @else
                            Toutes les Actualités
                        @endif
                    @endif
                </h2>
            @endif
            <div class="row g-4 mb-4" id="actualitesList">
                @foreach($showAll ? $actualites : $autresActualites as $actualite)
                    @if($selectedCategorie == 'all' || $actualite->categorie == $selectedCategorie)
                        <div class="col-md-6 col-lg-4 actualite-item" data-categorie="{{ $actualite->categorie }}">
                            <div class="actualite-card">
                                @if($actualite->image)
                                    <img src="{{ asset('storage/' . $actualite->image) }}" alt="{{ $actualite->titre }}" class="actualite-image">
                                @else
                                    <div class="actualite-image d-flex align-items-center justify-content-center text-white">
                                        <i style="font-size: 3rem;">📰</i>
                                    </div>
                                @endif
                                <div class="actualite-body">
                                    <div class="actualite-date">{{ optional($actualite->date_publication)->format('d M Y') }}</div>
                                    <h3 class="actualite-title">{{ $actualite->titre }}</h3>
                                    <p class="actualite-description">{{ Str::limit($actualite->description, 150) }}</p>
                                    @if($actualite->categorie)
                                        <span class="actualite-categorie">{{ $actualite->categorie }}</span>
                                    @endif
                                    <a href="{{ route('actualites.show', $actualite) }}" class="btn btn-sm mt-auto" style="background: var(--color-primary); color: white; border-radius: 8px;">
                                        Lire la suite →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            
            @if(!$showAll)
                @php
                    $totalActualites = \App\Models\Actualite::where('publie', true)
                        ->when($selectedCategorie != 'all', function($query) use ($selectedCategorie) {
                            return $query->where('categorie', $selectedCategorie);
                        })
                        ->count();
                @endphp
                @if($totalActualites > 9)
                <div class="text-center mb-5">
                    <a href="{{ route('actualites', ['filter' => 'all', 'categorie' => $selectedCategorie]) }}" class="btn" style="background: var(--color-primary); color: white; border-radius: 8px; padding: 0.75rem 2rem;">
                        Voir tout ({{ $totalActualites }} actualités)
                    </a>
                </div>
                @endif
            @else
                <!-- Pagination -->
                @if($actualites instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="d-flex justify-content-center mb-5">
                        {{ $actualites->appends(['filter' => 'all', 'categorie' => $selectedCategorie])->links() }}
                    </div>
                @endif
            @endif
            @endif

            @if(($showAll ? $actualites->count() : ($autresActualites->count() + ($premiereActualite ? 1 : 0))) == 0)
                <div class="text-center py-5">
                    <p class="text-muted">Aucune actualité disponible pour le moment.</p>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="actualites-sidebar">
                <!-- Catégories -->
                @if($allCategories->count() > 0)
                <div class="sidebar-section">
                    <h5>📂 Catégories</h5>
                    <ul class="categorie-list">
                        <li>
                            <a href="{{ route('actualites', ['filter' => $showAll ? 'all' : null, 'categorie' => 'all']) }}" 
                               class="categorie-link {{ $selectedCategorie == 'all' ? 'active' : '' }}">
                                Toutes les actualités
                                <span class="count">{{ \App\Models\Actualite::where('publie', true)->count() }}</span>
                            </a>
                        </li>
                        @foreach($allCategories as $cat)
                            <li>
                                <a href="{{ route('actualites', ['filter' => $showAll ? 'all' : null, 'categorie' => $cat->categorie]) }}" 
                                   class="categorie-link {{ $selectedCategorie == $cat->categorie ? 'active' : '' }}">
                                    {{ $cat->categorie }}
                                    <span class="count">{{ $cat->count }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Actualités récentes -->
                @if($recentActualites->count() > 0)
                <div class="sidebar-section">
                    <h5>📰 Récentes</h5>
                    <ul class="recent-actualites-list">
                        @foreach($recentActualites as $recent)
                            <li>
                                <a href="{{ route('actualites.show', $recent) }}" class="recent-actualite-link">
                                    <div class="title">{{ Str::limit($recent->titre, 60) }}</div>
                                    <div class="date">{{ optional($recent->date_publication)->format('d M Y') }}</div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Info box -->
                <div class="sidebar-section">
                    <div class="info-box">
                        <p><strong>💡 Astuce</strong><br>
                        Utilisez les catégories pour filtrer les actualités selon vos intérêts.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
