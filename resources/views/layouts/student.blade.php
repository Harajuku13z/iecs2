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
                        <a class="list-group-item list-group-item-action" href="{{ route('etudiant.dashboard') }}">Tableau de bord</a>
                        <a class="list-group-item list-group-item-action" href="{{ route('profile.edit') }}">Mon profil</a>
                        <a class="list-group-item list-group-item-action" href="{{ route('formations') }}">Formations</a>
                        <a class="list-group-item list-group-item-action" href="{{ route('admission') }}">Admission</a>
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


