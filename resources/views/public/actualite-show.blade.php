@extends('layouts.app')

@push('head')
<!-- Open Graph / Facebook -->
<meta property="og:type" content="article">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="{{ $actualite->titre }}">
<meta property="og:description" content="{{ Str::limit($actualite->description ?? $actualite->contenu, 160) }}">
@if($actualite->image)
<meta property="og:image" content="{{ asset('storage/' . $actualite->image) }}">
@endif

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ url()->current() }}">
<meta property="twitter:title" content="{{ $actualite->titre }}">
<meta property="twitter:description" content="{{ Str::limit($actualite->description ?? $actualite->contenu, 160) }}">
@if($actualite->image)
<meta property="twitter:image" content="{{ asset('storage/' . $actualite->image) }}">
@endif

<!-- SEO Meta -->
<meta name="description" content="{{ Str::limit($actualite->description ?? $actualite->contenu, 160) }}">
<meta name="keywords" content="{{ $actualite->categorie }}, IESCA, actualités">
@endpush

@section('title', $actualite->titre . ' - IESCA')

@section('content')
<style>
.actualite-show-hero {
    position: relative;
    min-height: 50vh;
    color: white;
    padding: 4rem 0;
    text-align: center;
    display: flex;
    align-items: center;
    overflow: hidden;
}

@php
    $heroImage = $actualite->image ? asset('storage/' . $actualite->image) : '';
@endphp

.actualite-show-hero {
    @if($heroImage)
    background-image: url('{{ $heroImage }}');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    @else
    background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
    @endif
}

.actualite-show-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(166, 96, 96, 0.7) 0%, rgba(13, 13, 13, 0.7) 100%);
    z-index: 0;
}

.actualite-show-hero-content {
    position: relative;
    z-index: 1;
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

.actualite-content-wrapper {
    max-width: 100%;
}

.actualite-content {
    background: white;
    border-radius: 8px;
    padding: 2.5rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.actualite-featured-image {
    width: 100%;
    max-height: 500px;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 2rem;
}

.actualite-meta {
    display: flex;
    gap: 1.5rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
}

.actualite-meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--color-primary);
    font-weight: 600;
    font-size: 0.95rem;
}

.actualite-description-box {
    background: var(--color-light);
    padding: 1.5rem;
    border-radius: 8px;
    border-left: 4px solid var(--color-primary);
    margin-bottom: 2rem;
    font-size: 1.15rem;
    line-height: 1.7;
    color: var(--color-dark);
    font-weight: 500;
}

.actualite-body {
    font-size: 1.1rem;
    line-height: 1.9;
    color: #333;
    margin-bottom: 2rem;
}

.actualite-body p {
    margin-bottom: 1.5rem;
}

.share-buttons {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
    padding: 1.5rem;
    background: var(--color-light);
    border-radius: 8px;
    margin-bottom: 2rem;
    align-items: center;
}

.share-buttons > span {
    font-weight: 700;
    color: var(--color-black);
    margin-right: 0.5rem;
}

.share-button {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-size: 0.9rem;
}

.share-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.share-button.facebook {
    background: #1877F2;
    color: white;
}

.share-button.twitter {
    background: #1DA1F2;
    color: white;
}

.share-button.linkedin {
    background: #0077B5;
    color: white;
}

.share-button.whatsapp {
    background: #25D366;
    color: white;
}

.share-button.copy {
    background: var(--color-primary);
    color: white;
}

.actualites-similaires {
    background: var(--color-light);
    padding: 4rem 0;
    margin-top: 3rem;
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

.actualite-body-card {
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
    text-align: center;
}

@media (max-width: 768px) {
    .actualites-sidebar {
        position: relative;
        top: 0;
    }
}
</style>

@php
    // Récupérer toutes les catégories avec comptage
    $allCategories = \App\Models\Actualite::where('publie', true)
        ->select('categorie', \DB::raw('count(*) as count'))
        ->groupBy('categorie')
        ->orderBy('count', 'desc')
        ->get();
    
    // Récupérer les actualités récentes pour la sidebar
    $recentActualites = \App\Models\Actualite::where('publie', true)
        ->where('id', '!=', $actualite->id)
        ->orderBy('date_publication', 'desc')
        ->take(5)
        ->get();
@endphp

<div class="actualite-show-hero">
    <div class="container">
        <div class="actualite-show-hero-content">
            <h1 style="font-size: 2.5rem; font-weight: 900; margin-bottom: 1.5rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">{{ $actualite->titre }}</h1>
            <div class="actualite-meta justify-content-center">
                <div class="actualite-meta-item">
                    <i style="font-size: 1.2rem;">📅</i>
                    <span>{{ optional($actualite->date_publication)->format('d M Y') }}</span>
                </div>
                @if($actualite->categorie)
                <div class="actualite-meta-item">
                    <i style="font-size: 1.2rem;">🏷️</i>
                    <span>{{ ucfirst($actualite->categorie) }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <!-- Contenu principal -->
        <div class="col-lg-9 mb-4">
            <div class="actualite-content">
                @if($actualite->image)
                    <img src="{{ asset('storage/' . $actualite->image) }}" alt="{{ $actualite->titre }}" class="actualite-featured-image">
                @endif

                @if($actualite->description)
                    <div class="actualite-description-box">
                        {{ $actualite->description }}
                    </div>
                @endif

                <div class="actualite-body">
                    {!! nl2br(e($actualite->contenu)) !!}
                </div>

                <!-- Boutons de partage -->
                <div class="share-buttons">
                    <span>📤 Partager :</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
                       target="_blank" 
                       class="share-button facebook"
                       onclick="window.open(this.href, 'facebook-share', 'width=626,height=436'); return false;">
                        <i>📘</i> Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($actualite->titre) }}" 
                       target="_blank" 
                       class="share-button twitter"
                       onclick="window.open(this.href, 'twitter-share', 'width=626,height=436'); return false;">
                        <i>🐦</i> Twitter
                    </a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" 
                       target="_blank" 
                       class="share-button linkedin"
                       onclick="window.open(this.href, 'linkedin-share', 'width=626,height=436'); return false;">
                        <i>💼</i> LinkedIn
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($actualite->titre . ' ' . url()->current()) }}" 
                       target="_blank" 
                       class="share-button whatsapp"
                       onclick="window.open(this.href, 'whatsapp-share', 'width=626,height=436'); return false;">
                        <i>💬</i> WhatsApp
                    </a>
                    <button onclick="copyToClipboard('{{ url()->current() }}')" class="share-button copy">
                        <i>📋</i> Copier
                    </button>
                </div>

                <div class="text-center">
                    <a href="{{ route('actualites') }}" class="btn" style="background: var(--color-primary); color: white; border-radius: 8px; padding: 0.75rem 2rem;">
                        ← Retour aux actualités
                    </a>
                </div>
            </div>
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
                            <a href="{{ route('actualites') }}" class="categorie-link">
                                Toutes les actualités
                                <span class="count">{{ \App\Models\Actualite::where('publie', true)->count() }}</span>
                            </a>
                        </li>
                        @foreach($allCategories as $cat)
                            <li>
                                <a href="{{ route('actualites', ['categorie' => $cat->categorie]) }}" 
                                   class="categorie-link {{ $actualite->categorie == $cat->categorie ? 'active' : '' }}">
                                    {{ ucfirst($cat->categorie) }}
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
                        Partagez cet article avec vos amis pour les tenir informés !</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($actualitesSimilaires->count() > 0)
<div class="actualites-similaires">
    <div class="container">
        <h2 class="section-title">Actualités similaires</h2>
        <div class="row g-4">
            @foreach($actualitesSimilaires as $similaire)
                <div class="col-md-4">
                    <div class="actualite-card">
                        @if($similaire->image)
                            <img src="{{ asset('storage/' . $similaire->image) }}" alt="{{ $similaire->titre }}" class="actualite-image">
                        @else
                            <div class="actualite-image d-flex align-items-center justify-content-center text-white">
                                <i style="font-size: 3rem;">📰</i>
                            </div>
                        @endif
                        <div class="actualite-body-card">
                            <div class="actualite-date">{{ optional($similaire->date_publication)->format('d M Y') }}</div>
                            <h3 class="actualite-title">{{ $similaire->titre }}</h3>
                            <p class="actualite-description">{{ Str::limit($similaire->description, 100) }}</p>
                            @if($similaire->categorie)
                                <span class="actualite-categorie">{{ $similaire->categorie }}</span>
                            @endif
                            <a href="{{ route('actualites.show', $similaire) }}" class="btn btn-sm mt-auto" style="background: var(--color-primary); color: white; border-radius: 8px;">
                                Lire la suite →
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Lien copié dans le presse-papiers !');
    }, function(err) {
        // Fallback pour les navigateurs plus anciens
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        alert('Lien copié dans le presse-papiers !');
    });
}
</script>
@endsection
