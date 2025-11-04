@extends('layouts.admin')

@section('title', 'Gestion des Événements')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>📅 Gestion des Événements</h1>
    <a href="{{ route('admin.evenements.create') }}" class="btn btn-primary">
        ➕ Nouvel Événement
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
                    <th>Type</th>
                    <th>Date Début</th>
                    <th>Lieu</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($evenements as $evenement)
                    <tr>
                        <td>{{ $evenement->id }}</td>
                        <td>
                            @if($evenement->image)
                                <img src="{{ asset('storage/' . $evenement->image) }}" alt="{{ $evenement->titre }}" 
                                     style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ Str::limit($evenement->titre, 50) }}</td>
                        <td>
                            <span class="badge bg-info">{{ $evenement->type }}</span>
                        </td>
                        <td>{{ $evenement->date_debut->format('d/m/Y H:i') }}</td>
                        <td>{{ $evenement->lieu ?? '—' }}</td>
                        <td>
                            @if($evenement->publie)
                                <span class="badge bg-success">Publié</span>
                            @else
                                <span class="badge bg-warning">Brouillon</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.evenements.edit', $evenement) }}" class="btn btn-warning">
                                    ✏️ Modifier
                                </a>
                                <form action="{{ route('admin.evenements.destroy', $evenement) }}" method="POST" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?');" class="d-inline">
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
                        <td colspan="8" class="text-center text-muted">Aucun événement trouvé</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $evenements->links() }}
        </div>
    </div>
</div>
@endsection

