@extends('layouts.admin')

@section('title', 'Gestion des Niveaux')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Gestion des Niveaux</h1>
    <a href="{{ route('admin.niveaux.create') }}" class="btn btn-primary">
        ➕ Nouveau Niveau
    </a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Ordre</th>
                    <th>Nb Classes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($niveaux as $niveau)
                    <tr>
                        <td>{{ $niveau->id }}</td>
                        <td>{{ $niveau->nom }}</td>
                        <td>{{ $niveau->ordre }}</td>
                        <td>{{ $niveau->classes->count() }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.niveaux.edit', $niveau) }}" class="btn btn-warning">✏️ Modifier</a>
                                <form action="{{ route('admin.niveaux.destroy', $niveau) }}" method="POST" 
                                      onsubmit="return confirm('Supprimer ce niveau ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">🗑️ Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Aucun niveau trouvé</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $niveaux->links() }}
    </div>
</div>
@endsection

