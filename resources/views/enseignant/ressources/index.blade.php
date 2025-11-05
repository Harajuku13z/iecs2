@extends('layouts.enseignant')

@section('teacher_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>📁 Mes Ressources</h3>
    <a href="{{ route('enseignant.ressources.create') }}" class="btn btn-primary">➕ Ajouter une ressource</a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($ressources->count() > 0)
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Type</th>
                            <th>Cours</th>
                            <th>Classe</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ressources as $ressource)
                            <tr>
                                <td>{{ $ressource->titre }}</td>
                                <td><span class="badge bg-secondary">{{ $ressource->type }}</span></td>
                                <td>{{ $ressource->cours->nom ?? 'N/A' }}</td>
                                <td>{{ $ressource->classe->nom ?? 'N/A' }}</td>
                                <td>{{ $ressource->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('enseignant.ressources.edit', $ressource) }}" class="btn btn-sm btn-outline-primary">✏️</a>
                                        <form action="{{ route('enseignant.ressources.destroy', $ressource) }}" method="POST" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette ressource ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">🗑️</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $ressources->links() }}
            </div>
        </div>
    </div>
@else
    <div class="alert alert-info">
        Aucune ressource partagée. <a href="{{ route('enseignant.ressources.create') }}">Ajouter une ressource</a>
    </div>
@endif
@endsection

