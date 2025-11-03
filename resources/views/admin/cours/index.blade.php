@extends('layouts.admin')

@section('title', 'Gestion des Cours')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Gestion des Cours</h1>
    <a href="{{ route('admin.cours.create') }}" class="btn btn-primary">➕ Nouveau Cours</a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Code</th>
                    <th>Coefficient</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cours as $cour)
                    <tr>
                        <td>{{ $cour->nom }}</td>
                        <td><code>{{ $cour->code }}</code></td>
                        <td>{{ $cour->coefficient }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.cours.edit', $cour) }}" class="btn btn-warning">✏️ Modifier</a>
                                <form action="{{ route('admin.cours.destroy', $cour) }}" method="POST" 
                                      onsubmit="return confirm('Supprimer ce cours ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">🗑️ Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">Aucun cours</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $cours->links() }}
    </div>
</div>
@endsection

