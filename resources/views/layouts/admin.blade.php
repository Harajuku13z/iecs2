<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - ' . config('app.name', 'IESCA'))</title>
    @php
        $faviconSetting = \App\Models\Setting::get('favicon', '');
        $faviconPath = $faviconSetting 
            ? asset('storage/' . $faviconSetting)
            : asset(config('app.favicon', '/favicon.ico'));
    @endphp
    <link rel="icon" type="image/x-icon" href="{{ $faviconPath }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ $faviconPath }}">
    {{-- Vite assets avec fallback --}}
    @if(file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        {{-- Fallback: Charger Bootstrap depuis CDN --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
        <link href="{{ asset('build/assets/app.css') }}" rel="stylesheet" onerror="this.onerror=null; this.href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css';">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @endif
    <style>
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">IESCA - Administration</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">Voir le site</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Déconnexion</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-md-block sidebar p-3">
                <div class="position-sticky">
                    <!-- Tableau de bord -->
                    <h6 class="text-muted mb-2 mt-3" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;">Tableau de bord</h6>
                    <ul class="nav flex-column mb-4">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                📊 Tableau de bord
                            </a>
                        </li>
                    </ul>

                    <!-- Gestion académique -->
                    <h6 class="text-muted mb-2 mt-3" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;">Gestion académique</h6>
                    <ul class="nav flex-column mb-4">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.filieres.index') }}">
                                📚 Filières
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.niveaux.index') }}">
                                📈 Niveaux
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.classes.index') }}">
                                🏫 Classes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.cours.index') }}">
                                📖 Cours
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.calendrier-cours.index') }}">
                                📅 Calendrier des Cours
                            </a>
                        </li>
                    </ul>

                    <!-- Gestion utilisateurs -->
                    <h6 class="text-muted mb-2 mt-3" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;">Utilisateurs</h6>
                    <ul class="nav flex-column mb-4">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.users.index') }}">
                                👥 Utilisateurs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.candidatures.index') }}">
                                📝 Candidatures
                            </a>
                        </li>
                    </ul>

                    <!-- Contenus -->
                    <h6 class="text-muted mb-2 mt-3" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;">Contenus</h6>
                    <ul class="nav flex-column mb-4">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.actualites.index') }}">
                                📰 Actualités
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.actualite-content.index') }}" style="padding-left: 1.5rem; font-size: 0.9rem;">
                                ⚙️ Config. Page Actualités
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.evenements.index') }}">
                                📅 Événements
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.notifications.index') }}">
                                🔔 Notifications
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.event-content.index') }}" style="padding-left: 1.5rem; font-size: 0.9rem;">
                                ⚙️ Config. Page Événements
                            </a>
                        </li>
                    </ul>

                    <!-- Pages -->
                    <h6 class="text-muted mb-2 mt-3" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;">Pages</h6>
                    <ul class="nav flex-column mb-4">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.home-content.index') }}">
                                🏠 Page d'Accueil
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.admission-content.index') }}">
                                📋 Page Admission
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.static-pages.index') }}">
                                📄 Pages Statiques
                            </a>
                        </li>
                    </ul>

                    <!-- Paramètres -->
                    <h6 class="text-muted mb-2 mt-3" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;">Paramètres</h6>
                    <ul class="nav flex-column mb-4">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.settings.index') }}">
                                ⚙️ Paramètres
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto px-md-4 py-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('modals')
    @stack('scripts')
</body>
</html>


