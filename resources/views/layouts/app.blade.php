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
            --color-primary: {{ \App\Models\Setting::get('color_primary', '#A66060') }};
            --color-secondary: {{ \App\Models\Setting::get('color_secondary', '#9E5A59') }};
            --color-light: {{ \App\Models\Setting::get('color_light', '#F2F2F2') }};
            --color-dark: {{ \App\Models\Setting::get('color_dark', '#595959') }};
            --color-black: {{ \App\Models\Setting::get('color_black', '#0D0D0D') }};
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
    <nav class="navbar navbar-expand-lg" style="background: white; box-shadow: 0 2px 20px rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 1000;">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                @php
                    $logo = \App\Models\Setting::get('logo', '');
                @endphp
                @if($logo)
                    <img src="{{ asset('storage/' . $logo) }}" alt="IESCA Logo" style="height: 50px; margin-right: 10px;">
                @endif
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="border-color: var(--color-black);">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}" style="color: var(--color-black); font-weight: 500;">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('formations') }}" style="color: var(--color-black); font-weight: 500;">Formations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admission') }}" style="color: var(--color-black); font-weight: 500;">Admission</a>
                    </li>
                    @auth
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}" style="color: var(--color-black); font-weight: 500;">Admin</a>
                            </li>
                        @elseif(auth()->user()->isEnseignant())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('enseignant.dashboard') }}" style="color: var(--color-black); font-weight: 500;">Enseignant</a>
                            </li>
                        @elseif(auth()->user()->isEtudiant() || auth()->user()->isCandidat())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('etudiant.dashboard') }}" style="color: var(--color-black); font-weight: 500;">Espace Étudiant</a>
                            </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" style="color: var(--color-black); font-weight: 500;">
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
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}" style="color: var(--color-black); font-weight: 500;">Connexion</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer style="background: linear-gradient(135deg, var(--color-black) 0%, var(--color-dark) 100%); color: white; padding: 4rem 0 2rem;">
        <div class="container">
            <div class="row g-4 mb-4">
                <!-- Colonne 1: À propos + Suivez-nous -->
                <div class="col-lg-3 col-md-6">
                    <h5 style="font-weight: 700; margin-bottom: 1.5rem; color: var(--color-primary);">IESCA</h5>
                    <p style="opacity: 0.8; line-height: 1.8; margin-bottom: 2rem; font-size: 0.9rem;">Institut d'Enseignement Supérieur de la Côte Africaine - Excellence académique et innovation.</p>
                    
                    <!-- Réseaux Sociaux -->
                    <div>
                        <h6 style="font-weight: 700; margin-bottom: 1rem;">Suivez-nous</h6>
                        <div class="d-flex gap-2 flex-wrap">
                            @php
                                $facebook = \App\Models\Setting::get('social_facebook', '');
                                $twitter = \App\Models\Setting::get('social_twitter', '');
                                $instagram = \App\Models\Setting::get('social_instagram', '');
                                $linkedin = \App\Models\Setting::get('social_linkedin', '');
                                $youtube = \App\Models\Setting::get('social_youtube', '');
                            @endphp
                            
                            @if($facebook)
                                <a href="{{ $facebook }}" target="_blank" style="width: 40px; height: 40px; background: var(--color-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none; transition: all 0.3s;" onmouseover="this.style.transform='scale(1.1)'; this.style.background='var(--color-secondary)'" onmouseout="this.style.transform='scale(1)'; this.style.background='var(--color-primary)'">
                                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/></svg>
                                </a>
                            @endif
                            @if($twitter)
                                <a href="{{ $twitter }}" target="_blank" style="width: 40px; height: 40px; background: var(--color-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none; transition: all 0.3s;" onmouseover="this.style.transform='scale(1.1)'; this.style.background='var(--color-secondary)'" onmouseout="this.style.transform='scale(1)'; this.style.background='var(--color-primary)'">
                                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z"/></svg>
                                </a>
                            @endif
                            @if($instagram)
                                <a href="{{ $instagram }}" target="_blank" style="width: 40px; height: 40px; background: var(--color-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none; transition: all 0.3s;" onmouseover="this.style.transform='scale(1.1)'; this.style.background='var(--color-secondary)'" onmouseout="this.style.transform='scale(1)'; this.style.background='var(--color-primary)'">
                                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"/></svg>
                                </a>
                            @endif
                            @if($linkedin)
                                <a href="{{ $linkedin }}" target="_blank" style="width: 40px; height: 40px; background: var(--color-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none; transition: all 0.3s;" onmouseover="this.style.transform='scale(1.1)'; this.style.background='var(--color-secondary)'" onmouseout="this.style.transform='scale(1)'; this.style.background='var(--color-primary)'">
                                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248-.822 0-1.359.54-1.359 1.248 0 .694.521 1.248 1.327 1.248h.016zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016a5.54 5.54 0 0 1 .016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225h2.4z"/></svg>
                                </a>
                            @endif
                            @if($youtube)
                                <a href="{{ $youtube }}" target="_blank" style="width: 40px; height: 40px; background: var(--color-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none; transition: all 0.3s;" onmouseover="this.style.transform='scale(1.1)'; this.style.background='var(--color-secondary)'" onmouseout="this.style.transform='scale(1)'; this.style.background='var(--color-primary)'">
                                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.007 2.007 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.007 2.007 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31.4 31.4 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.007 2.007 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A99.788 99.788 0 0 1 7.858 2h.193zM6.4 5.209v4.818l4.157-2.408L6.4 5.209z"/></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Colonne 2: Liens Rapides & Informations -->
                <div class="col-lg-3 col-md-6">
                    <h6 style="font-weight: 700; margin-bottom: 1.5rem;">Liens Rapides</h6>
                    <ul style="list-style: none; padding: 0; margin-bottom: 2rem;">
                        <li style="margin-bottom: 0.75rem;"><a href="{{ route('formations') }}" style="color: white; opacity: 0.8; text-decoration: none; transition: all 0.3s;" onmouseover="this.style.opacity='1'; this.style.paddingLeft='10px'" onmouseout="this.style.opacity='0.8'; this.style.paddingLeft='0'">→ Nos Formations</a></li>
                        <li style="margin-bottom: 0.75rem;"><a href="{{ route('admission') }}" style="color: white; opacity: 0.8; text-decoration: none; transition: all 0.3s;" onmouseover="this.style.opacity='1'; this.style.paddingLeft='10px'" onmouseout="this.style.opacity='0.8'; this.style.paddingLeft='0'">→ Admission</a></li>
                    </ul>
                    
                    <h6 style="font-weight: 700; margin-bottom: 1rem;">Informations</h6>
                    <ul style="list-style: none; padding: 0;">
                        <li style="margin-bottom: 0.75rem;"><a href="#" style="color: white; opacity: 0.8; text-decoration: none; transition: all 0.3s;" onmouseover="this.style.opacity='1'; this.style.paddingLeft='10px'" onmouseout="this.style.opacity='0.8'; this.style.paddingLeft='0'">→ FAQ</a></li>
                        <li style="margin-bottom: 0.75rem;"><a href="#" style="color: white; opacity: 0.8; text-decoration: none; transition: all 0.3s;" onmouseover="this.style.opacity='1'; this.style.paddingLeft='10px'" onmouseout="this.style.opacity='0.8'; this.style.paddingLeft='0'">→ Conditions d'utilisation</a></li>
                    </ul>
                </div>
                
                <!-- Colonne 3: Espaces -->
                <div class="col-lg-3 col-md-6">
                    <h6 style="font-weight: 700; margin-bottom: 1.5rem;">Espaces</h6>
                    <ul style="list-style: none; padding: 0;">
                        <li style="margin-bottom: 0.75rem;"><a href="{{ route('etudiant.dashboard') }}" style="color: white; opacity: 0.8; text-decoration: none; transition: all 0.3s;" onmouseover="this.style.opacity='1'; this.style.paddingLeft='10px'" onmouseout="this.style.opacity='0.8'; this.style.paddingLeft='0'">→ Espace Étudiant</a></li>
                        <li style="margin-bottom: 0.75rem;"><a href="{{ route('enseignant.dashboard') }}" style="color: white; opacity: 0.8; text-decoration: none; transition: all 0.3s;" onmouseover="this.style.opacity='1'; this.style.paddingLeft='10px'" onmouseout="this.style.opacity='0.8'; this.style.paddingLeft='0'">→ Espace Enseignant</a></li>
                        <li style="margin-bottom: 0.75rem;"><a href="{{ route('admin.dashboard') }}" style="color: white; opacity: 0.8; text-decoration: none; transition: all 0.3s;" onmouseover="this.style.opacity='1'; this.style.paddingLeft='10px'" onmouseout="this.style.opacity='0.8'; this.style.paddingLeft='0'">→ Espace Admin</a></li>
                        <li style="margin-bottom: 0.75rem;"><a href="{{ route('login') }}" style="color: white; opacity: 0.8; text-decoration: none; transition: all 0.3s;" onmouseover="this.style.opacity='1'; this.style.paddingLeft='10px'" onmouseout="this.style.opacity='0.8'; this.style.paddingLeft='0'">→ Connexion</a></li>
                    </ul>
                </div>
                
                <!-- Colonne 4: Contact -->
                <div class="col-lg-3 col-md-6">
                    <h6 style="font-weight: 700; margin-bottom: 1.5rem;">Contact</h6>
                    <p style="opacity: 0.8; line-height: 2;">
                        <strong>📍 Adresse:</strong><br>
                        {{ \App\Models\Setting::get('address', '112, Avenue de France (Poto poto)') }}<br><br>
                        <strong>📞 Téléphones:</strong><br>
                        <a href="tel:{{ \App\Models\Setting::get('phone1', '+242065419861') }}" style="color: white; opacity: 0.8; text-decoration: none;">{{ \App\Models\Setting::get('phone1', '+242 06 541 98 61') }}</a><br>
                        <a href="tel:{{ \App\Models\Setting::get('phone2', '+242050226408') }}" style="color: white; opacity: 0.8; text-decoration: none;">{{ \App\Models\Setting::get('phone2', '+242 05 022 64 08') }}</a><br><br>
                        <strong>📧 Email:</strong><br>
                        <a href="mailto:{{ \App\Models\Setting::get('email', 'institutenseignementsupérieur@gmail.com') }}" style="color: white; opacity: 0.8; text-decoration: none;">{{ \App\Models\Setting::get('email', 'institutenseignementsupérieur@gmail.com') }}</a>
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
