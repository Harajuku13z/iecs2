@php
    $logo = \App\Models\Setting::get('logo', '');
    $logoUrl = $logo ? asset('storage/' . $logo) : null;
    $email = \App\Models\Setting::get('email', 'institutenseignementsupérieur@gmail.com');
    $phone = \App\Models\Setting::get('phone1', '+242 06 541 98 61');
@endphp

<div class="container py-5" style="min-height: 80vh; display:flex; align-items:center;">
    <div class="row justify-content-center w-100">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm" style="border-radius: 8px; overflow:hidden;">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        @if($logoUrl)
                            <img src="{{ $logoUrl }}" alt="IESCA" style="height: 60px;">
                        @endif
                        <h1 class="mt-3 mb-1" style="font-size:1.6rem; font-weight:800;">Connexion</h1>
                        <p class="text-muted mb-0">Accédez à votre espace</p>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="form-control" />
                            @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mot de passe</label>
                            <input id="password" type="password" name="password" required autocomplete="current-password" class="form-control" />
                            @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Se souvenir de moi</label>
                            </div>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-decoration-none" style="color: var(--color-primary); font-weight:600;">Mot de passe oublié ?</a>
                            @endif
                        </div>

                        <button type="submit" class="btn w-100" style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); color: #fff; font-weight:700;">Se connecter</button>
                    </form>

                    <div class="mt-4 text-center text-muted" style="font-size:.9rem;">
                        <div>Besoin d’aide ? <a href="mailto:{{ $email }}" class="text-decoration-none" style="color: var(--color-primary);">{{ $email }}</a></div>
                        <div>ou appelez le {{ $phone }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
