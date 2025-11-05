@extends('layouts.admin')

@section('title', 'Cours de ' . $classe->nom)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>📚 Cours de {{ $classe->nom }}</h1>
    <div>
        <a href="{{ route('admin.cours.create') }}" class="btn btn-primary">➕ Ajouter des cours</a>
        <a href="{{ route('admin.cours.index') }}" class="btn btn-secondary">← Retour</a>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <p><strong>Filière:</strong> {{ $classe->filiere->nom ?? '-' }}</p>
        <p><strong>Niveau:</strong> {{ $classe->niveau->nom ?? '-' }}</p>
        <p><strong>Total cours:</strong> {{ $classe->cours->count() }}</p>
    </div>
</div>

@forelse($coursParSemestre as $semestre => $cours)
<div class="card mb-3">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Semestre {{ $semestre }}</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Code</th>
                        <th>Coefficient</th>
                        <th>Description</th>
                        <th>Enseignant(s)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cours as $c)
                        <tr>
                            <td>{{ $c->nom }}</td>
                            <td><code>{{ $c->code }}</code></td>
                            <td>{{ $c->coefficient }}</td>
                            <td>{{ Str::limit($c->description ?? '-', 50) }}</td>
                            <td>
                                @php
                                    $enseignants = \DB::table('cours_user')
                                        ->join('users', 'cours_user.user_id', '=', 'users.id')
                                        ->where('cours_user.cours_id', $c->id)
                                        ->where('cours_user.classe_id', $classe->id)
                                        ->pluck('users.name')
                                        ->toArray();
                                @endphp
                                @if(count($enseignants) > 0)
                                    {{ implode(', ', $enseignants) }}
                                @else
                                    <span class="text-muted">Aucun enseignant</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.cours.edit', $c) }}" class="btn btn-warning">✏️</a>
                                    <form action="{{ route('admin.cours.detach', $c) }}" method="POST" 
                                          onsubmit="return confirm('Retirer ce cours de cette classe ?');" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="classe_id" value="{{ $classe->id }}">
                                        <button type="submit" class="btn btn-danger">🗑️ Retirer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@empty
<div class="alert alert-info">
    Aucun cours assigné à cette classe pour le moment.
</div>
@endforelse
@endsection



