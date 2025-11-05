@extends('layouts.admin')

@section('title', 'Gestion des Cours')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>📚 Gestion des Cours</h1>
    <a href="{{ route('admin.cours.create') }}" class="btn btn-primary">➕ Ajouter des Cours</a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Classe</th>
                        <th>Filière</th>
                        <th>Niveau</th>
                        <th>Nombre de cours</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classes as $classe)
                        <tr>
                            <td><strong>{{ $classe->nom }}</strong></td>
                            <td>{{ $classe->filiere->nom ?? '-' }}</td>
                            <td>{{ $classe->niveau->nom ?? '-' }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $classe->cours->count() }} cours</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.cours.classe.show', $classe) }}" class="btn btn-sm btn-primary">
                                    👁️ Voir les cours
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Aucune classe trouvée</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
