@extends('layouts.admin')

@section('title', 'Gestion des Actualités')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>📰 Gestion des Actualités</h1>
    <a href="{{ route('admin.actualites.create') }}" class="btn btn-primary">
        ➕ Nouvelle Actualité
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
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Titre</th>
                    <th>Catégorie</th>
                    <th>Date Publication</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($actualites as $actualite)
                    <tr>
                        <td>{{ $actualite->id }}</td>
                        <td>
                            @if($actualite->image)
                                <img src="{{ asset('storage/' . $actualite->image) }}" alt="{{ $actualite->titre }}" 
                                     style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ Str::limit($actualite->titre, 50) }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ $actualite->categorie }}</span>
                        </td>
                        <td>{{ $actualite->date_publication->format('d/m/Y') }}</td>
                        <td>
                            @if($actualite->publie)
                                <span class="badge bg-success">Publié</span>
                            @else
                                <span class="badge bg-warning">Brouillon</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.actualites.edit', $actualite) }}" class="btn btn-warning">
                                    ✏️ Modifier
                                </a>
                                <form action="{{ route('admin.actualites.destroy', $actualite) }}" method="POST" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette actualité ?');" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        🗑️ Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Aucune actualité trouvée</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $actualites->links() }}
        </div>
    </div>
</div>
@endsection

