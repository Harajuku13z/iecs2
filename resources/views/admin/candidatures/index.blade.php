@extends('layouts.admin')

@section('title', 'Gestion des Candidatures')

@section('content')
<h1 class="mb-4">Gestion des Candidatures</h1>

<div class="card">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Candidat</th>
                    <th>Email</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($candidatures as $candidature)
                    <tr>
                        <td>{{ $candidature->user->name }}</td>
                        <td>{{ $candidature->user->email }}</td>
                        <td>
                            @if($candidature->statut === 'soumis')
                                <span class="badge bg-warning">Soumis</span>
                            @elseif($candidature->statut === 'verifie')
                                <span class="badge bg-info">Vérifié</span>
                            @elseif($candidature->statut === 'admis')
                                <span class="badge bg-success">Admis</span>
                            @else
                                <span class="badge bg-danger">Rejeté</span>
                            @endif
                        </td>
                        <td>{{ $candidature->created_at->format('d/m/Y') }}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal{{ $candidature->id }}">
                                Gérer
                            </button>
                        </td>
                    </tr>

                    <!-- Modal -->
                    <div class="modal fade" id="modal{{ $candidature->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Gérer la candidature</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.candidatures.updateStatus', $candidature) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Statut</label>
                                            <select name="statut" class="form-select" required>
                                                <option value="soumis" {{ $candidature->statut === 'soumis' ? 'selected' : '' }}>Soumis</option>
                                                <option value="verifie" {{ $candidature->statut === 'verifie' ? 'selected' : '' }}>Vérifié</option>
                                                <option value="admis" {{ $candidature->statut === 'admis' ? 'selected' : '' }}>Admis</option>
                                                <option value="rejete" {{ $candidature->statut === 'rejete' ? 'selected' : '' }}>Rejeté</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Classe (si admis)</label>
                                            <select name="classe_id" class="form-select">
                                                <option value="">Sélectionner une classe</option>
                                                @foreach($classes as $classe)
                                                    <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Commentaire</label>
                                            <textarea name="commentaire_admin" class="form-control" rows="3">{{ $candidature->commentaire_admin }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Aucune candidature</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $candidatures->links() }}
        </div>
    </div>
</div>
@endsection

