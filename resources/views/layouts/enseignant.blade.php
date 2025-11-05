@extends('layouts.app')

@section('content')
<style>
    .teacher-sidebar {
        background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(166, 96, 96, 0.2);
        overflow: hidden;
    }
    
    .teacher-sidebar-header {
        background: rgba(255, 255, 255, 0.1);
        padding: 1.5rem;
        color: white;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .teacher-sidebar-header h5 {
        color: white;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }
    
    .teacher-sidebar-header small {
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.85rem;
    }
    
    .teacher-nav-item {
        background: transparent;
        border: none;
        color: rgba(255, 255, 255, 0.9);
        padding: 1rem 1.5rem;
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        text-decoration: none;
        width: 100%;
        text-align: left;
    }
    
    .teacher-nav-item:hover {
        background: rgba(255, 255, 255, 0.15);
        color: white;
        border-left-color: white;
        transform: translateX(5px);
    }
    
    .teacher-nav-item.active {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border-left-color: white;
        font-weight: 600;
    }
    
    .teacher-nav-divider {
        border-color: rgba(255, 255, 255, 0.2);
        margin: 0.5rem 0;
    }
    
    .teacher-content-wrapper {
        background: #f8f9fa;
        min-height: 100vh;
        padding: 2rem 0;
    }
    
    .teacher-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        border: none;
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .teacher-card:hover {
        box-shadow: 0 8px 24px rgba(166, 96, 96, 0.15);
        transform: translateY(-2px);
    }
    
    .teacher-card-header {
        background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
        color: white;
        padding: 1.25rem 1.5rem;
        font-weight: 600;
        border: none;
    }
    
    .teacher-stat-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        border: none;
        transition: all 0.3s ease;
        overflow: hidden;
        border-top: 4px solid var(--color-primary);
    }
    
    .teacher-stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(166, 96, 96, 0.2);
    }
    
    .teacher-stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .teacher-card .table thead {
        background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
        color: white;
    }
    
    .teacher-card .table thead th {
        border: none;
        font-weight: 600;
        padding: 1rem;
    }
    
    .teacher-card .table tbody tr {
        transition: all 0.3s ease;
    }
    
    .teacher-card .table tbody tr:hover {
        background: rgba(166, 96, 96, 0.05);
        transform: scale(1.01);
    }
    
    .teacher-card .list-group-item {
        border: none;
        border-bottom: 1px solid #f0f0f0;
        padding: 1rem;
        transition: all 0.3s ease;
    }
    
    .teacher-card .list-group-item:hover {
        background: rgba(166, 96, 96, 0.05);
        border-left: 3px solid var(--color-primary);
        padding-left: calc(1rem - 3px);
    }
    
    .teacher-card .list-group-item:last-child {
        border-bottom: none;
    }
    
    @media (max-width: 991px) {
        .teacher-sidebar {
            margin-bottom: 2rem;
        }
        
        .teacher-nav-item {
            padding: 0.875rem 1.25rem;
        }
    }
</style>

<div class="teacher-content-wrapper">
    <div class="container">
        <div class="row g-4">
            <div class="col-12 col-lg-3">
                <div class="teacher-sidebar">
                    <div class="teacher-sidebar-header">
                        <h5>👨‍🏫 Espace Enseignant</h5>
                        <small>{{ Auth::user()->name }}</small>
                    </div>

                    <!-- Mobile: select navigation -->
                    <div class="d-lg-none p-3">
                        <select class="form-select mb-3" onchange="if(this.value){window.location.href=this.value;}">
                            <option value="">Aller à…</option>
                            <option value="{{ route('enseignant.dashboard') }}" {{ request()->is('enseignant/dashboard') ? 'selected' : '' }}>📊 Tableau de bord</option>
                            <option value="{{ route('enseignant.cours.index') }}" {{ request()->is('enseignant/cours*') ? 'selected' : '' }}>📚 Mes Cours</option>
                            <option value="{{ route('enseignant.classes.index') }}" {{ request()->is('enseignant/classes*') ? 'selected' : '' }}>🏫 Mes Classes</option>
                            <option value="{{ route('enseignant.notes.index') }}" {{ request()->is('enseignant/notes*') ? 'selected' : '' }}>📊 Notes</option>
                            <option value="{{ route('enseignant.examens.index') }}" {{ request()->is('enseignant/examens*') ? 'selected' : '' }}>📝 Examens</option>
                            <option value="{{ route('enseignant.ressources.index') }}" {{ request()->is('enseignant/ressources*') ? 'selected' : '' }}>📁 Ressources</option>
                            <option value="{{ route('enseignant.notifications.index') }}" {{ request()->is('enseignant/notifications*') ? 'selected' : '' }}>🔔 Notifications</option>
                            <option value="{{ route('profile.edit') }}" {{ request()->is('profile*') ? 'selected' : '' }}>👤 Mon Profil</option>
                        </select>
                    </div>

                    <!-- Desktop: list navigation -->
                    <div class="d-none d-lg-block">
                        <a class="teacher-nav-item {{ request()->is('enseignant/dashboard') ? 'active' : '' }}" href="{{ route('enseignant.dashboard') }}">
                            📊 Tableau de bord
                        </a>
                        <a class="teacher-nav-item {{ request()->is('enseignant/cours*') ? 'active' : '' }}" href="{{ route('enseignant.cours.index') }}">
                            📚 Mes Cours
                        </a>
                        <a class="teacher-nav-item {{ request()->is('enseignant/classes*') ? 'active' : '' }}" href="{{ route('enseignant.classes.index') }}">
                            🏫 Mes Classes
                        </a>
                        <a class="teacher-nav-item {{ request()->is('enseignant/notes*') ? 'active' : '' }}" href="{{ route('enseignant.notes.index') }}">
                            📊 Notes
                        </a>
                        <a class="teacher-nav-item {{ request()->is('enseignant/examens*') ? 'active' : '' }}" href="{{ route('enseignant.examens.index') }}">
                            📝 Examens
                        </a>
                        <a class="teacher-nav-item {{ request()->is('enseignant/ressources*') ? 'active' : '' }}" href="{{ route('enseignant.ressources.index') }}">
                            📁 Ressources
                        </a>
                        <a class="teacher-nav-item {{ request()->is('enseignant/notifications*') ? 'active' : '' }}" href="{{ route('enseignant.notifications.index') }}">
                            🔔 Notifications
                        </a>
                        <hr class="teacher-nav-divider">
                        <a class="teacher-nav-item {{ request()->is('profile*') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                            👤 Mon Profil
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-9">
                @yield('teacher_content')
            </div>
        </div>
    </div>
</div>
@endsection

