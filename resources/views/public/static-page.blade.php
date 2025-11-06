@extends('layouts.app')

@section('title', $page->titre . ' - IESCA')

@push('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" referrerpolicy="no-referrer" />
@endpush

@section('meta_description', $page->description ?: Str::limit(strip_tags($page->contenu), 160))

@section('content')
<!-- Hero Section -->
@if($page->image_principale)
<div class="hero-section-static" style="background: linear-gradient(135deg, rgba(166, 96, 96, 0.7) 0%, rgba(13, 13, 13, 0.7) 100%), url('{{ asset('storage/' . $page->image_principale) }}') center/cover; min-height: 40vh; display: flex; align-items: center; color: white; padding: 80px 0 40px;">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-3">{{ $page->titre }}</h1>
                @if($page->description)
                    <p class="lead">{{ $page->description }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@else
<div class="container mt-5 pt-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="display-5 fw-bold mb-3">{{ $page->titre }}</h1>
            @if($page->description)
                <p class="lead text-muted">{{ $page->description }}</p>
            @endif
        </div>
    </div>
</div>
@endif

<!-- Content Section -->
<div class="container my-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="page-content">
                @if($page->type_contenu === 'html')
                    {!! $page->contenu !!}
                @else
                    <div style="white-space: pre-line; line-height: 1.8;">{{ $page->contenu }}</div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.hero-section-static {
    position: relative;
    overflow: hidden;
}

.page-content i.fa, .page-content i.fas, .page-content i.far, .page-content i.fab {
    margin-right: .35rem;
}

.page-content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #333;
}

.page-content h1,
.page-content h2,
.page-content h3,
.page-content h4,
.page-content h5,
.page-content h6 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    color: var(--color-primary, #A66060);
    font-weight: 700;
}

.page-content h1 { font-size: 2.5rem; }
.page-content h2 { font-size: 2rem; }
.page-content h3 { font-size: 1.75rem; }
.page-content h4 { font-size: 1.5rem; }
.page-content h5 { font-size: 1.25rem; }
.page-content h6 { font-size: 1rem; }

.page-content p {
    margin-bottom: 1.5rem;
}

.page-content ul,
.page-content ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
}

.page-content li {
    margin-bottom: 0.5rem;
}

.page-content img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 2rem 0;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.page-content blockquote {
    border-left: 4px solid var(--color-primary, #A66060);
    padding-left: 1.5rem;
    margin: 2rem 0;
    font-style: italic;
    color: #666;
}

.page-content a {
    color: var(--color-primary, #A66060);
    text-decoration: none;
    border-bottom: 2px solid transparent;
    transition: all 0.3s ease;
}

.page-content a:hover {
    border-bottom-color: var(--color-primary, #A66060);
}

.page-content table {
    width: 100%;
    margin: 2rem 0;
    border-collapse: collapse;
}

.page-content table th,
.page-content table td {
    padding: 0.75rem;
    border: 1px solid #ddd;
}

.page-content table th {
    background-color: var(--color-primary, #A66060);
    color: white;
    font-weight: 600;
}

.page-content code {
    background-color: #f4f4f4;
    padding: 0.2rem 0.4rem;
    border-radius: 4px;
    font-family: 'Courier New', monospace;
    font-size: 0.9em;
}

.page-content pre {
    background-color: #f4f4f4;
    padding: 1rem;
    border-radius: 8px;
    overflow-x: auto;
    margin: 2rem 0;
}

.page-content pre code {
    background: none;
    padding: 0;
}
</style>
@endsection

