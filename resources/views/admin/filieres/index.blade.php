@extends('layouts.admin')

@section('title', 'Gestion des Filières')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Gestion des Filières</h1>
    <a href="{{ route('admin.filieres.create') }}" class="btn btn-primary">
        ➕ Nouvelle Filière
    </a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Nb Classes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($filieres as $filiere)
                    <tr>
                        <td>{{ $filiere->id }}</td>
                        <td>{{ $filiere->nom }}</td>
                        <td>{{ Str::limit($filiere->description, 50) }}</td>
                        <td>{{ $filiere->classes->count() }}</td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.filieres.edit', $filiere) }}" class="btn btn-warning">
                                    ✏️ Modifier
                                </a>
                                <form action="{{ route('admin.filieres.destroy', $filiere) }}" method="POST" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette filière ?');">
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
                        <td colspan="5" class="text-center text-muted">Aucune filière trouvée</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $filieres->links() }}
        </div>
    </div>
</div>
@endsection

