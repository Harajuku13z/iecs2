@extends('layouts.admin')

@section('title', 'Calendrier des Cours')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>📅 Calendrier des Cours</h1>
    <a href="{{ route('admin.calendrier-cours.create') }}" class="btn btn-primary">
        ➕ Ajouter des cours
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        @if($classes->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Classe</th>
                            <th>Filière</th>
                            <th>Niveau</th>
                            <th>Nombre de cours</th>
                            <th>Semestres</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($classes as $classe)
                            <tr>
                                <td>
                                    <strong>{{ $classe->nom }}</strong>
                                </td>
                                <td>{{ optional($classe->filiere)->nom ?? '-' }}</td>
                                <td>{{ optional($classe->niveau)->nom ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $classe->nb_cours_calendrier ?? 0 }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $classe->nb_semestres ?? 0 }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.calendrier-cours.show', $classe) }}" class="btn btn-sm btn-primary">
                                        📅 Voir le calendrier
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <p class="text-muted">Aucune classe avec calendrier. <a href="{{ route('admin.calendrier-cours.create') }}">Ajouter le premier calendrier</a></p>
            </div>
        @endif
    </div>
</div>
@endsection

