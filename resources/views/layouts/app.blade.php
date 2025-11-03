<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'IESCA')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <style>
        :root {
            --color-primary: #A66060;
            --color-secondary: #9E5A59;
            --color-light: #F2F2F2;
            --color-dark: #595959;
            --color-black: #0D0D0D;
        }
        body {
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }
        main {
            padding: 0 !important;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: var(--color-primary); backdrop-filter: blur(10px); box-shadow: 0 2px 20px rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 1000;">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">IESCA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/') }}">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('formations') }}">Formations</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admission') }}">Admission</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Connexion</a>
                        </li>
                    @else
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">Tableau de bord Admin</a>
                            </li>
                        @elseif(auth()->user()->isEnseignant())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('enseignant.dashboard') }}">Tableau de bord Enseignant</a>
                            </li>
                        @elseif(auth()->user()->isEtudiant())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('etudiant.dashboard') }}">Tableau de bord Étudiant</a>
                            </li>
                        @elseif(auth()->user()->isCandidat())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('etudiant.dashboard') }}">Suivi Candidature</a>
                            </li>
                        @endif
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
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer style="background: linear-gradient(135deg, #1E1E1E 0%, #2d2d2d 100%); color: white; padding: 4rem 0 2rem;">
        <div class="container">
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <h5 style="font-weight: 700; margin-bottom: 1.5rem; color: #C09F5F;">IESCA</h5>
                    <p style="opacity: 0.8; line-height: 1.8;">Institut d'Enseignement Supérieur de la Côte Africaine - Excellence académique et innovation.</p>
                </div>
                <div class="col-md-4">
                    <h6 style="font-weight: 700; margin-bottom: 1.5rem;">Liens Rapides</h6>
                    <ul style="list-style: none; padding: 0;">
                        <li style="margin-bottom: 0.75rem;"><a href="{{ route('formations') }}" style="color: white; opacity: 0.8; text-decoration: none;">Nos Formations</a></li>
                        <li style="margin-bottom: 0.75rem;"><a href="{{ route('admission') }}" style="color: white; opacity: 0.8; text-decoration: none;">Admission</a></li>
                        <li style="margin-bottom: 0.75rem;"><a href="{{ route('login') }}" style="color: white; opacity: 0.8; text-decoration: none;">Connexion</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6 style="font-weight: 700; margin-bottom: 1.5rem;">Contact</h6>
                    <p style="opacity: 0.8; line-height: 1.8;">
                        📍 Abidjan, Côte d'Ivoire<br>
                        📧 contact@iesca.com<br>
                        📞 +225 XX XX XX XX XX
                    </p>
                </div>
            </div>
            <hr style="opacity: 0.2; margin: 2rem 0;">
            <div class="text-center" style="opacity: 0.7;">
                <p class="mb-0">&copy; {{ date('Y') }} IESCA - Institut d'Enseignement Supérieur. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
</body>
</html>
