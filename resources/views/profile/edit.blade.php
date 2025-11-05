@extends('layouts.app')

@section('title', 'Mon Profil')

@section('content')
<style>
.profile-hero {
    background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
    color: white;
    padding: 2.5rem 0;
}
.profile-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    border: 1px solid #eee;
}
.form-label { font-weight: 600; }
</style>

<div class="profile-hero">
    <div class="container">
        <h1 class="mb-0">Mon Profil</h1>
        <p class="mb-0" style="opacity: .9;">Gérez vos informations personnelles</p>
                </div>
            </div>

<div class="container py-5">
    @if (session('status') === 'profile-updated')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Vos informations ont été mises à jour.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('status') === 'password-updated')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Votre mot de passe a été mis à jour.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="profile-card p-4 p-md-5">
                <h5 class="mb-4">Informations personnelles</h5>
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Veuillez corriger les erreurs ci-dessous.</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('patch')

                    <div class="mb-3">
                        <label for="name" class="form-label">Nom complet</label>
                        <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autocomplete="name">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="username">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Téléphone</label>
                        <input id="phone" name="phone" type="text" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" autocomplete="tel">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Adresse</label>
                        <input id="address" name="address" type="text" class="form-control @error('address') is-invalid @enderror" value="{{ old('address', $user->address) }}" autocomplete="street-address">
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="profile_photo" class="form-label">Photo de profil</label>
                        <div class="d-flex align-items-center gap-3">
                            <div>
                                @if($user->profile_photo)
                                    <img id="profilePreview" src="{{ asset('storage/' . $user->profile_photo) }}" alt="Photo" style="width: 72px; height: 72px; border-radius: 50%; object-fit: cover; border: 3px solid var(--color-light);">
                                @else
                                    <div id="profilePreview" style="width: 72px; height: 72px; border-radius: 50%; background: var(--color-light); display: flex; align-items: center; justify-content: center;">👤</div>
                                @endif
                            </div>
                            <input class="form-control @error('profile_photo') is-invalid @enderror" type="file" id="profile_photo" name="profile_photo" accept="image/*" onchange="previewProfile(event)">
                            @error('profile_photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <script>
                            function previewProfile(e){
                                const file = e.target.files[0];
                                if(!file) return;
                                const reader = new FileReader();
                                reader.onload = () => {
                                    const el = document.getElementById('profilePreview');
                                    if (el.tagName.toLowerCase() === 'img') {
                                        el.src = reader.result;
                                    } else {
                                        const img = document.createElement('img');
                                        img.src = reader.result;
                                        img.style.width = '72px';
                                        img.style.height = '72px';
                                        img.style.borderRadius = '50%';
                                        img.style.objectFit = 'cover';
                                        img.style.border = '3px solid var(--color-light)';
                                        el.replaceWith(img);
                                        img.id = 'profilePreview';
                                    }
                                };
                                reader.readAsDataURL(file);
                            }
                        </script>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Annuler</a>
                    </div>
                </form>
                </div>
            </div>

        <div class="col-lg-4">
            <div class="profile-card p-4 p-md-5 mb-4">
                <h6 class="mb-3">Changer mon mot de passe</h6>
                @if ($errors->updatePassword?->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Impossible de mettre à jour le mot de passe.</strong> Vérifiez les champs.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                <form method="post" action="{{ route('password.update') }}">
                    @csrf
                    @method('put')
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Mot de passe actuel</label>
                        <input id="current_password" type="password" name="current_password" class="form-control @error('updatePassword.current_password') is-invalid @enderror" required autocomplete="current-password">
                        @error('updatePassword.current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Nouveau mot de passe</label>
                        <input id="new_password" type="password" name="password" class="form-control @error('updatePassword.password') is-invalid @enderror" required autocomplete="new-password">
                        @error('updatePassword.password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Mettre à jour le mot de passe</button>
                </form>
                </div>
            <div class="profile-card p-4 p-md-5">
                <h6 class="mb-3">Aide</h6>
                <p class="text-muted mb-0">Besoin d'assistance ? Contactez l'administration.</p>
            </div>
        </div>
    </div>
</div>
@endsection
