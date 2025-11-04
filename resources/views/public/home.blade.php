@extends('layouts.app')

@section('title', 'Accueil - IESCA')

@section('content')
<style>
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap');

* {
    font-family: 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
}

h1, h2, h3, h4, h5, h6 {
    font-family: 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
}

/* Hero Section - Collé au header */
.hero-section {
    position: relative;
    min-height: 90vh;
    background: linear-gradient(135deg, rgba(166, 96, 96, 0.5) 0%, rgba(13, 13, 13, 0.5) 100%),
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
    font-size: clamp(1.8rem, 4vw, 3rem);
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: 1rem;
    text-shadow: 2px 4px 20px rgba(0,0,0,0.5);
}

.hero-subtitle {
    font-size: clamp(0.95rem, 1.5vw, 1.2rem);
    font-weight: 400;
    opacity: 0.95;
    margin-bottom: 2rem;
    letter-spacing: 0.3px;
}

/* Hero Text Content */
.hero-text-content {
    text-align: left;
}

/* Premium Search Box */
.premium-search {
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(20px);
    border-radius: 8px;
    padding: 1rem 1.5rem;
    box-shadow: 
        0 25px 50px rgba(0,0,0,0.3),
        0 0 0 1px rgba(255,255,255,0.1) inset;
    transform: translateY(0);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    width: 100%;
    max-width: 100%;
}

.premium-search:hover {
    transform: translateY(-5px);
    box-shadow: 0 30px 60px rgba(0,0,0,0.4);
}

.search-input {
    height: 45px;
    border: 2px solid #e8e8e8;
    border-radius: 6px;
    padding: 0 1rem;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    background: white;
    cursor: pointer;
}

.search-input:focus {
    border-color: var(--color-primary);
    box-shadow: 0 0 0 4px rgba(166, 96, 96, 0.1);
    outline: none;
}

.search-button {
    height: 45px;
    background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
    border: none;
    border-radius: 6px;
    color: white;
    font-weight: 600;
    font-size: 0.95rem;
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

/* Filières Horizontal Scroll Section */
.filieres-scroll-section {
    padding: 6rem 0;
    background: white;
}

.filieres-scroll-section .section-title {
    color: var(--color-black) !important;
    background: none !important;
    -webkit-background-clip: unset !important;
    -webkit-text-fill-color: var(--color-black) !important;
    background-clip: unset !important;
}

.filieres-scroll-container {
    position: relative;
    overflow: hidden;
}

.filieres-scroll-wrapper {
    display: flex;
    gap: 2rem;
    overflow-x: auto;
    overflow-y: hidden;
    padding: 1rem 0 2rem;
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: thin;
    scrollbar-color: var(--color-primary) transparent;
}

.filieres-scroll-wrapper::-webkit-scrollbar {
    height: 8px;
}

.filieres-scroll-wrapper::-webkit-scrollbar-track {
    background: var(--color-light);
    border-radius: 4px;
}

.filieres-scroll-wrapper::-webkit-scrollbar-thumb {
    background: var(--color-primary);
    border-radius: 4px;
}

.filiere-scroll-card {
    min-width: 320px;
    max-width: 320px;
    background: white;
    border: 2px solid var(--color-light);
    border-radius: 8px;
    padding: 0;
    transition: all 0.3s ease;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    display: flex;
    flex-direction: column;
    position: relative;
    overflow: hidden;
}

.filiere-scroll-card:hover {
    border-color: var(--color-primary);
    transform: translateY(-5px);
    box-shadow: 0 10px 40px rgba(166, 96, 96, 0.15);
}

.filiere-card-image {
    width: 100%;
    height: 200px;
    overflow: hidden;
    background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
    display: flex;
    align-items: center;
    justify-content: center;
}

.filiere-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.filiere-scroll-card:hover .filiere-card-image img {
    transform: scale(1.1);
}

.filiere-card-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.filiere-card-icon {
    font-size: 4rem;
    color: white;
}

.filiere-scroll-card .filiere-card-title {
    padding: 1.5rem 1.5rem 0.5rem;
    margin-bottom: 0.5rem;
}

.filiere-scroll-card .filiere-card-description {
    padding: 0 1.5rem;
    margin-bottom: 1rem;
}

.filiere-scroll-card .filiere-card-link {
    padding: 0 1.5rem 1.5rem;
}

.filiere-card-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--color-black);
    margin-bottom: 1rem;
    line-height: 1.3;
}

.filiere-card-description {
    font-size: 0.95rem;
    color: var(--color-dark);
    line-height: 1.6;
    margin-bottom: 1.5rem;
    flex-grow: 1;
}

.filiere-card-link {
    color: var(--color-primary);
    text-decoration: none;
    font-weight: 600;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.filiere-card-link:hover {
    color: var(--color-secondary);
    gap: 0.75rem;
}

.stat-card {
    background: white;
    border-radius: 8px;
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
    border-radius: 8px;
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
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    flex-shrink: 0;
}

/* Admission Process Section */
.admission-process-section {
    padding: 6rem 0;
    background: white;
}

.admission-image-container {
    position: relative;
    overflow: hidden;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding-left: 0;
}

/* Border left alignée avec le container principal */
@media (min-width: 576px) {
    .admission-image-container::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 15px;
        background: white;
        z-index: 1;
    }
}

@media (min-width: 768px) {
    .admission-image-container::before {
        width: calc((100vw - 720px) / 2);
    }
}

@media (min-width: 992px) {
    .admission-image-container::before {
        width: calc((100vw - 960px) / 2);
    }
}

@media (min-width: 1200px) {
    .admission-image-container::before {
        width: calc((100vw - 1140px) / 2);
    }
}

@media (min-width: 1400px) {
    .admission-image-container::before {
        width: calc((100vw - 1320px) / 2);
    }
}

.admission-process-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    min-height: 0;
    border-radius: 8px;
}

.admission-process-content {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.admission-process-title {
    font-weight: 800;
    color: var(--color-black);
    margin-bottom: 1rem;
}

.admission-process-intro {
    font-size: 1.2rem;
    color: var(--color-dark);
    margin-bottom: 3rem;
    line-height: 1.6;
}

.admission-steps {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.admission-step-item {
    display: flex;
    gap: 1.5rem;
    align-items: start;
}

.step-number {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    font-weight: 900;
    flex-shrink: 0;
    box-shadow: 0 10px 30px rgba(166, 96, 96, 0.3);
}

.step-content {
    flex: 1;
}

.step-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--color-black);
    margin-bottom: 0.5rem;
}

.step-description {
    font-size: 1rem;
    color: var(--color-dark);
    line-height: 1.6;
    margin: 0;
}

.admission-cta-button {
    display: inline-block;
    background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
    color: white;
    padding: 1rem 2.5rem;
    border-radius: 6px;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 10px 30px rgba(166, 96, 96, 0.3);
}

.admission-cta-button:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(166, 96, 96, 0.4);
    color: white;
}

/* Responsive pour Processus d'Admission */
@media (max-width: 991px) {
    .admission-process-section .row {
        flex-direction: column-reverse;
    }
    
    /* Forcer un format carré sur mobile pour l'image */
    .admission-image-container {
        min-height: 0;
        aspect-ratio: 1 / 1;
    }
    .admission-process-image {
        min-height: 0;
        height: 100%;
    }
    
    .admission-process-content {
        padding: 2rem 1.5rem !important;
    }
    
    .admission-process-title {
        font-size: 2.5rem;
    }
}

/* Admission Timeline (old) */
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
    font-size: clamp(1.8rem, 3vw, 2rem);
    font-weight: 800;
    margin-bottom: 1rem;
    background: linear-gradient(135deg, white, rgba(255,255,255,0.8));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Harmoniser la taille des titres des sections principales */
.about-section h2,
.admission-process-title,
.filieres-scroll-section .section-title,
.news-section .section-title {
    font-size: clamp(1.8rem, 3vw, 2rem) !important;
    font-weight: 800 !important;
    -webkit-text-fill-color: initial;
    background: none;
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
    border-radius: 8px;
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
    border-radius: 8px;
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
    background: none !important;
    -webkit-background-clip: unset !important;
    -webkit-text-fill-color: var(--color-black) !important;
    background-clip: unset !important;
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
    border-radius: 8px;
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
    border-radius: 6px;
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
    border-radius: 8px;
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
    border-radius: 8px;
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
    border-radius: 4px;
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
    border-radius: 4px;
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
    border-radius: 6px;
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
    
    .hero-text-content {
        text-align: center;
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
@php
    $heroImage = \App\Models\Setting::get('hero_image', '');
    $heroImageUrl = $heroImage ? asset('storage/' . $heroImage) : 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=1920';
@endphp
<section class="hero-section" style="background: linear-gradient(135deg, rgba(166, 96, 96, 0.5) 0%, rgba(13, 13, 13, 0.5) 100%), url('{{ $heroImageUrl }}') center/cover;">
    <div class="container">
        <div class="hero-content">
            <div class="row">
                <div class="col-lg-6 col-md-8 col-12">
                    <div class="hero-text-content">
                        <h1 class="hero-title" data-aos="fade-up">
                            {{ \App\Models\Setting::get('hero_title', 'Façonnons l\'Avenir de l\'Excellence') }}
                        </h1>
                        <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="100">
                            {{ \App\Models\Setting::get('hero_subtitle', 'Institut d\'Enseignement Supérieur de la Côte Africaine') }}
                        </p>
                    </div>
                    
                </div>
            </div>
            <div class="row justify-content-center mt-4">
                <div class="col-12">
                    <!-- Premium Search Box - Style Voyage -->
                    <div class="premium-search" data-aos="fade-up" data-aos-delay="200">
                        <h4 class="text-center text-dark mb-2" style="font-weight: 700; font-size: 1.3rem;">Trouvez Votre Formation Idéale</h4>
                        <form id="formationSearchForm" action="{{ route('formations') }}" method="GET">
                            <div class="row g-2 align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label text-dark mb-1" style="font-weight: 600; font-size: 0.85rem;">📚 Niveau d'étude</label>
                                    <select class="form-select search-input" id="niveauSelect" name="niveau_id" required style="height: 45px; font-size: 0.9rem;">
                                        <option value="">Sélectionnez un niveau</option>
                                        @php
                                            $niveaux = \App\Models\Niveau::orderBy('ordre')->get();
                                        @endphp
                                        @foreach($niveaux as $niveau)
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
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label text-dark mb-1" style="font-weight: 600; font-size: 0.85rem;">🎓 Filière</label>
                                    <select class="form-select search-input" id="filiereSelect" name="filiere_id" required disabled style="height: 45px; font-size: 0.9rem;">
                                        <option value="">Sélectionnez d'abord un niveau</option>
                                        @foreach(\App\Models\Filiere::all() as $filiere)
                                            <option value="{{ $filiere->id }}" data-filiere="{{ $filiere->nom }}">{{ $filiere->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn search-button w-100" id="searchButton" disabled style="height: 45px; font-size: 0.95rem;">
                                        🔍 Rechercher
                                    </button>
                                </div>
                            </div>
                        </form>
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
                    <h2>{{ \App\Models\Setting::get('about_title', 'À Propos de l\'IESCA') }}</h2>
                    <p>
                        {{ \App\Models\Setting::get('about_text1', 'L\'Institut d\'Enseignement Supérieur de la Côte Africaine (IESCA) est un établissement d\'excellence situé au 112, Avenue de France (Poto poto), dédié à la formation de leaders et d\'innovateurs.') }}
                    </p>
                    <p>
                        {{ \App\Models\Setting::get('about_text2', 'Nous offrons des formations de qualité en Licence dans 4 domaines clés : Sciences et Administration des Affaires, Génie Informatique, Sciences Juridiques et Sciences Commerciales.') }}
                    </p>
                    
                    <div class="about-features">
                        @for($i = 1; $i <= 6; $i++)
                            <div class="feature-item">
                                <div class="feature-icon">{{ \App\Models\Setting::get('about_feature_' . $i . '_icon', ['💻', '📚', '❄️', '👨‍🏫', '📹', '🏢'][$i-1]) }}</div>
                                <div>
                                    <h5 style="font-weight: 700; margin-bottom: 0.5rem;">{{ \App\Models\Setting::get('about_feature_' . $i . '_title', ['Salle d\'Informatique', 'Bibliothèque', 'Classes Climatisées', 'Formation Complète', 'Caméras de Surveillance', 'Stage Garanti'][$i-1]) }}</h5>
                                    <p style="color: var(--color-dark); margin: 0;">{{ \App\Models\Setting::get('about_feature_' . $i . '_description', ['Équipements modernes et performants', 'Ressources académiques complètes', 'Confort optimal pour l\'apprentissage', 'Cours théoriques et pratiques', 'Sécurité assurée 24/7', 'En fin de formation'][$i-1]) }}</p>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="about-image">
                    @php
                        $aboutImage = \App\Models\Setting::get('about_image', '');
                        $aboutImageUrl = $aboutImage ? asset('storage/' . $aboutImage) : 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?w=800';
                    @endphp
                    <img src="{{ $aboutImageUrl }}" alt="Campus IESCA">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Filières Horizontal Scroll Section -->
<section class="filieres-scroll-section">
    <div class="container">
        <div class="section-header text-dark" data-aos="fade-up">
            <h2 class="section-title">{{ \App\Models\Setting::get('filieres_title', 'Découvrez nos formations d\'excellence') }}</h2>
        </div>
        
        <div class="filieres-scroll-container" data-aos="fade-up" data-aos-delay="100">
            <div class="filieres-scroll-wrapper">
                @foreach(\App\Models\Filiere::all() as $filiere)
                    <div class="filiere-scroll-card">
                        <div class="filiere-card-image">
                            @if($filiere->image)
                                <img src="{{ asset('storage/' . $filiere->image) }}" alt="{{ $filiere->nom }}">
                            @else
                                <div class="filiere-card-placeholder">
                                    <div class="filiere-card-icon">🎓</div>
                                </div>
                            @endif
                        </div>
                        <h3 class="filiere-card-title">{{ $filiere->nom }}</h3>
                                <p class="filiere-card-description">{{ Str::limit($filiere->description ?? 'Formation d\'excellence', 100) }}</p>
                                @if($filiere->specialites && $filiere->specialites->count())
                                    <div class="d-flex flex-wrap gap-1 mb-2">
                                        @foreach($filiere->specialites as $specialite)
                                            <span class="badge bg-light text-dark border">{{ $specialite->nom }}</span>
                                        @endforeach
                                    </div>
                                @endif
                        <a href="{{ route('formations', ['filiere_id' => $filiere->id]) }}" class="filiere-card-link">
                            En savoir plus →
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Filières en vedette (3 grandes cartes) -->
@php
    $featuredFilieres = \App\Models\Filiere::with('specialites')->take(3)->get();
@endphp
@if($featuredFilieres->count())
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            @foreach($featuredFilieres as $f)
                <div class="col-12 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <a href="{{ route('formations', ['filiere_id' => $f->id]) }}" class="text-decoration-none">
                        <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 12px;">
                            <div style="position: relative; aspect-ratio: 16 / 9; background:#f7f7f7;">
                                @if($f->image)
                                    <img src="{{ asset('storage/' . $f->image) }}" alt="{{ $f->nom }}" style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover;">
                                @else
                                    <div class="d-flex align-items-center justify-content-center h-100 w-100" style="font-size:3rem;">🎓</div>
                                @endif
                                <div style="position:absolute; inset:0; background:linear-gradient(180deg, rgba(0,0,0,0.0) 30%, rgba(0,0,0,0.6) 100%);"></div>
                                <div style="position:absolute; left:1rem; right:1rem; bottom:1rem; color:#fff;">
                                    <h4 class="mb-1" style="font-weight:800;">{{ $f->nom }}</h4>
                                    @if($f->specialites && $f->specialites->count())
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($f->specialites->take(3) as $sp)
                                                <span class="badge bg-light text-dark">{{ $sp->nom }}</span>
                                            @endforeach
                                            @if($f->specialites->count() > 3)
                                                <span class="badge bg-secondary">+{{ $f->specialites->count() - 3 }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="text-muted mb-0">{{ Str::limit($f->description ?? 'Formation d\'excellence', 110) }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    </section>
@endif

<!-- Processus d'Admission Section -->
<section class="admission-process-section">
    <div class="container-fluid px-0">
        <div class="row g-0 align-items-stretch">
            <!-- Image à gauche (9:16) -->
            <div class="col-lg-6" data-aos="fade-right">
                <div class="admission-image-container h-100" style="width: 100%; height: 100%;">
                    @php
                        $admissionImage = \App\Models\Setting::get('admission_process_image', 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=600&h=1067&fit=crop');
                        $admissionImageUrl = $admissionImage ? (str_starts_with($admissionImage, 'http') ? $admissionImage : asset('storage/' . $admissionImage)) : 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=600&h=1067&fit=crop';
                    @endphp
                    <img src="{{ $admissionImageUrl }}" alt="Processus d'Admission IESCA" class="admission-process-image">
                </div>
            </div>
            
            <!-- Contenu à droite -->
            <div class="col-lg-6" data-aos="fade-left">
                <div class="admission-process-content h-100" style="width: 100%; padding: 3rem;">
                    <h2 class="admission-process-title">{{ \App\Models\Setting::get('admission_process_title', 'Processus d\'Admission') }}</h2>
                    <p class="admission-process-intro">{{ \App\Models\Setting::get('admission_process_intro', 'Quatre étapes simples pour rejoindre l\'excellence à l\'IESCA') }}</p>
                    
                    <div class="admission-steps">
                        <div class="admission-step-item" data-aos="fade-up" data-aos-delay="100">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <h4 class="step-title">{{ \App\Models\Setting::get('admission_step_1_title', 'Inscription en Ligne') }}</h4>
                                <p class="step-description">{{ \App\Models\Setting::get('admission_step_1_description', 'Créez votre compte et soumettez votre dossier de candidature en quelques clics.') }}</p>
                            </div>
                        </div>
                        
                        <div class="admission-step-item" data-aos="fade-up" data-aos-delay="200">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h4 class="step-title">{{ \App\Models\Setting::get('admission_step_2_title', 'Vérification Administrative') }}</h4>
                                <p class="step-description">{{ \App\Models\Setting::get('admission_step_2_description', 'Notre équipe examine votre dossier sous 48h.') }}</p>
                            </div>
                        </div>
                        
                        <div class="admission-step-item" data-aos="fade-up" data-aos-delay="300">
                            <div class="step-number">3</div>
                            <div class="step-content">
                                <h4 class="step-title">{{ \App\Models\Setting::get('admission_step_3_title', 'Évaluation du Comité') }}</h4>
                                <p class="step-description">{{ \App\Models\Setting::get('admission_step_3_description', 'Le comité d\'admission étudie votre profil académique.') }}</p>
                            </div>
                        </div>
                        
                        <div class="admission-step-item" data-aos="fade-up" data-aos-delay="400">
                            <div class="step-number">4</div>
                            <div class="step-content">
                                <h4 class="step-title">{{ \App\Models\Setting::get('admission_step_4_title', 'Décision d\'Admission') }}</h4>
                                <p class="step-description">{{ \App\Models\Setting::get('admission_step_4_description', 'Recevez votre décision par email.') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4" data-aos="fade-up" data-aos-delay="500">
                        <a href="{{ route('admission') }}" class="admission-cta-button">
                            Commencer Ma Candidature →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- News Section -->
<section class="news-section">
    <div class="container">
        <div class="section-header text-dark" data-aos="fade-up">
            <h2 class="section-title" style="color: var(--color-black) !important;">Actualités IESCA</h2>
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
@php
    $ctaBgImage = \App\Models\Setting::get('cta_background_image', '');
    $ctaBgImageUrl = $ctaBgImage ? asset('storage/' . $ctaBgImage) : '';
@endphp
<section class="cta-section" style="@if($ctaBgImageUrl)background: linear-gradient(135deg, rgba(166, 96, 96, 0.85) 0%, rgba(13, 13, 13, 0.85) 100%), url('{{ $ctaBgImageUrl }}') center/cover;@else background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-black) 100%);@endif">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 cta-content" data-aos="fade-right">
                <h2 class="cta-title">{{ \App\Models\Setting::get('cta_title', 'Prêt à Rejoindre l\'Excellence ?') }}</h2>
                <p class="cta-subtitle">{{ \App\Models\Setting::get('cta_subtitle', 'Les inscriptions sont ouvertes. Commencez votre parcours vers le succès.') }}</p>
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
    const formationForm = document.getElementById('formationSearchForm');
    
    if (!niveauSelect || !filiereSelect || !searchButton || !formationForm) {
      console.error('Éléments du formulaire non trouvés');
      return;
    }
    
    console.log('Form elements found:', { niveauSelect, filiereSelect, searchButton, formationForm });
    
    // Stocker toutes les filières
    const allFilieres = [
      @foreach(\App\Models\Filiere::all() as $filiere)
        {id: {{ $filiere->id }}, nom: "{{ addslashes($filiere->nom) }}"},
      @endforeach
    ];
    
    console.log('Filieres loaded:', allFilieres.length);
    
    // Fonction pour activer/désactiver le bouton
    function updateSearchButton() {
      if (niveauSelect.value && filiereSelect.value && !filiereSelect.disabled) {
        searchButton.disabled = false;
        searchButton.style.opacity = '1';
        searchButton.style.cursor = 'pointer';
      } else {
        searchButton.disabled = true;
        searchButton.style.opacity = '0.6';
        searchButton.style.cursor = 'not-allowed';
      }
    }
    
    // Quand un niveau est sélectionné, activer le select filière
    niveauSelect.addEventListener('change', function(e) {
      console.log('Niveau changed:', this.value);
      if (this.value && this.value !== '') {
        filiereSelect.disabled = false;
        filiereSelect.style.opacity = '1';
        filiereSelect.style.cursor = 'pointer';
        filiereSelect.innerHTML = '<option value="">Sélectionnez une filière</option>';
        allFilieres.forEach(function(filiere) {
          const option = document.createElement('option');
          option.value = filiere.id;
          option.textContent = filiere.nom;
          filiereSelect.appendChild(option);
        });
        filiereSelect.value = ''; // Réinitialiser la sélection
      } else {
        filiereSelect.disabled = true;
        filiereSelect.style.opacity = '0.6';
        filiereSelect.style.cursor = 'not-allowed';
        filiereSelect.innerHTML = '<option value="">Sélectionnez d\'abord un niveau</option>';
        filiereSelect.value = '';
      }
      updateSearchButton();
    });
    
    // Quand une filière est sélectionnée, activer le bouton
    filiereSelect.addEventListener('change', function(e) {
      console.log('Filiere changed:', this.value);
      updateSearchButton();
    });
    
    // Validation du formulaire avant soumission
    formationForm.addEventListener('submit', function(e) {
      if (!niveauSelect.value || !filiereSelect.value || filiereSelect.disabled) {
        e.preventDefault();
        alert('Veuillez sélectionner un niveau et une filière avant de rechercher.');
        return false;
      }
    });
    
    // Initialiser l'état du bouton
    updateSearchButton();
    
    // Vérifier si le niveau select a des options
    console.log('Niveau options:', niveauSelect.options.length);
  });
</script>
@endsection
