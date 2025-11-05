@extends('layouts.app')

@section('title', 'Inscription - IESCA')

@section('content')
@php
    $logo = \App\Models\Setting::get('logo', '');
    $logoUrl = $logo ? asset('storage/' . $logo) : null;
    $email = \App\Models\Setting::get('email', 'institutenseignementsupérieur@gmail.com');
    $phone = \App\Models\Setting::get('phone1', '+242 06 541 98 61');
@endphp

<div style="background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);">
<div class="container py-5" style="min-height: 80vh; display:flex; align-items:center;">
    <div class="row justify-content-center w-100">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm" style="border-radius: 8px; overflow:hidden;">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        @if($logoUrl)
                            <img src="{{ $logoUrl }}" alt="IESCA" style="height: 60px;">
                        @endif
                        <h1 class="mt-3 mb-1" style="font-size:1.6rem; font-weight:800;">Créer un compte</h1>
                        <p class="text-muted mb-0">Rejoignez l'IESCA</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        {{-- Préserver les paramètres de formation --}}
                        @if(isset($formationParams))
                            @if($formationParams['filiere_id'])
                                <input type="hidden" name="filiere_id" value="{{ $formationParams['filiere_id'] }}">
                            @endif
                            @if($formationParams['specialite_id'])
                                <input type="hidden" name="specialite_id" value="{{ $formationParams['specialite_id'] }}">
                            @endif
                            @if($formationParams['classe_id'])
                                <input type="hidden" name="classe_id" value="{{ $formationParams['classe_id'] }}">
                            @endif
                        @endif

                        <div class="mb-3">
                            <label class="form-label">Nom complet</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="form-control" />
                            @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Téléphone</label>
                            <input id="phone" type="text" name="phone" value="{{ old('phone') }}" autocomplete="tel" class="form-control" />
                            @error('phone')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Adresse</label>
                            <input id="address" type="text" name="address" value="{{ old('address') }}" autocomplete="street-address" class="form-control" />
                            @error('address')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="form-control" />
                            @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mot de passe</label>
                            <input id="password" type="password" name="password" required autocomplete="new-password" class="form-control" />
                            @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirmer le mot de passe</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="form-control" />
                            @error('password_confirmation')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <button type="submit" class="btn w-100" style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); color: #fff; font-weight:700;">Créer mon compte</button>
                    </form>

                    <div class="mt-3 text-center">
                        <a href="{{ route('login') }}" class="text-decoration-none" style="color: var(--color-primary); font-weight:600;">Déjà inscrit ? Se connecter</a>
                    </div>

                    <div class="mt-4 text-center text-muted" style="font-size:.9rem;">
                        <div>Besoin d’aide ? <a href="mailto:{{ $email }}" class="text-decoration-none" style="color: var(--color-primary);">{{ $email }}</a></div>
                        <div>ou appelez le {{ $phone }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
