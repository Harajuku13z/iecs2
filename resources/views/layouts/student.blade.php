@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row g-4">
        <div class="col-12 col-lg-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-grow-1">
                            <h5 class="mb-0">Espace Étudiant</h5>
                            <small class="text-muted">{{ Auth::user()->name }}</small>
                        </div>
                    </div>
                    <div class="list-group list-group-flush">
                        @php
                            $candidature = auth()->user()->candidature;
                            $candidatureValidee = $candidature && $candidature->statut === 'admis';
                        @endphp
                        
                        @if($candidatureValidee)
                            <a class="list-group-item list-group-item-action {{ request()->is('etudiant/dashboard') ? 'active' : '' }}" href="{{ route('etudiant.dashboard') }}">
                                📊 Tableau de bord
                            </a>
                            <a class="list-group-item list-group-item-action {{ request()->is('etudiant/cours*') ? 'active' : '' }}" href="{{ route('etudiant.cours.index') }}">
                                📚 Mes Cours
                            </a>
                            <a class="list-group-item list-group-item-action {{ request()->is('etudiant/notes*') ? 'active' : '' }}" href="{{ route('etudiant.notes.index') }}">
                                📊 Mes Notes
                            </a>
                            <a class="list-group-item list-group-item-action {{ request()->is('etudiant/calendrier*') ? 'active' : '' }}" href="{{ route('etudiant.calendrier.index') }}">
                                📅 Calendrier
                            </a>
                            <a class="list-group-item list-group-item-action {{ request()->is('etudiant/ressources*') ? 'active' : '' }}" href="{{ route('etudiant.ressources.index') }}">
                                📁 Ressources
                            </a>
                            <a class="list-group-item list-group-item-action {{ request()->is('etudiant/notifications*') ? 'active' : '' }}" href="{{ route('etudiant.notifications.index') }}">
                                🔔 Notifications
                                @if(auth()->user()->notificationsNonLues()->count() > 0)
                                    <span class="badge bg-danger rounded-pill">{{ auth()->user()->notificationsNonLues()->count() }}</span>
                                @endif
                            </a>
                        @endif
                        
                        <a class="list-group-item list-group-item-action {{ request()->is('etudiant/candidature*') ? 'active' : '' }}" href="{{ route('etudiant.candidature.show') }}">
                            📋 Ma Candidature
                        </a>
                        <hr class="my-2">
                        <a class="list-group-item list-group-item-action {{ request()->is('profile*') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                            👤 Mon Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-9">
            @yield('student_content')
        </div>
    </div>
</div>
@endsection


