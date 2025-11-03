@extends('layouts.admin')

@section('title', 'Gestion des Classes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Gestion des Classes</h1>
    <a href="{{ route('admin.classes.create') }}" class="btn btn-primary">➕ Nouvelle Classe</a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Filière</th>
                    <th>Niveau</th>
                    <th>Nb Étudiants</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($classes as $classe)
                    <tr>
                        <td>{{ $classe->nom }}</td>
                        <td>{{ $classe->filiere->nom }}</td>
                        <td>{{ $classe->niveau->nom }}</td>
                        <td>{{ $classe->etudiants->count() }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.classes.edit', $classe) }}" class="btn btn-warning">✏️ Modifier</a>
                                <form action="{{ route('admin.classes.destroy', $classe) }}" method="POST" 
                                      onsubmit="return confirm('Supprimer cette classe ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">🗑️ Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Aucune classe</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $classes->links() }}
    </div>
</div>
@endsection

