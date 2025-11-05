@extends('layouts.enseignant')

@section('teacher_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>📊 Mes Notes</h3>
    <a href="{{ route('enseignant.notes.create') }}" class="btn btn-primary">➕ Saisir des notes</a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Filtres -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('enseignant.notes.index') }}" id="filterForm">
            <div class="row g-3">
                <div class="col-md-5">
                    <label for="classe_id" class="form-label">📚 Filtrer par Classe</label>
                    <select class="form-select" id="classe_id" name="classe_id" onchange="updateCoursList()">
                        <option value="">Toutes les classes</option>
                        @foreach($classes as $classe)
                            <option value="{{ $classe->id }}" {{ $classeId == $classe->id ? 'selected' : '' }}>
                                {{ $classe->nom }} - {{ optional($classe->filiere)->nom }} ({{ optional($classe->niveau)->nom }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-5">
                    <label for="cours_id" class="form-label">📖 Filtrer par Cours</label>
                    <select class="form-select" id="cours_id" name="cours_id" {{ !$classeId ? 'disabled' : '' }}>
                        <option value="">Tous les cours</option>
                        @if($classeId && $cours->count() > 0)
                            @foreach($cours as $c)
                                <option value="{{ $c->id }}" {{ $coursId == $c->id ? 'selected' : '' }}>
                                    {{ $c->nom }} @if($c->code)({{ $c->code }})@endif
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">🔍 Filtrer</button>
                </div>
            </div>
            
            @if($classeId || $coursId)
                <div class="mt-3">
                    <a href="{{ route('enseignant.notes.index') }}" class="btn btn-sm btn-outline-secondary">❌ Réinitialiser les filtres</a>
                </div>
            @endif
        </form>
    </div>
</div>

@if($notes->count() > 0)
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                📋 Notes 
                @if($classeId)
                    - Classe: <strong>{{ $classes->firstWhere('id', $classeId)->nom ?? '' }}</strong>
                @endif
                @if($coursId)
                    - Cours: <strong>{{ $cours->firstWhere('id', $coursId)->nom ?? '' }}</strong>
                @endif
                <span class="badge bg-primary ms-2">{{ $notes->total() }} note(s)</span>
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Étudiant</th>
                            <th>Classe</th>
                            <th>Cours</th>
                            <th>Note</th>
                            <th>Type</th>
                            <th>Semestre</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($notes as $note)
                            <tr>
                                <td>
                                    <strong>{{ $note->etudiant->name ?? 'N/A' }}</strong>
                                    @if($note->etudiant->email)
                                        <br><small class="text-muted">{{ $note->etudiant->email }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($note->etudiant && $note->etudiant->classe)
                                        <span class="badge bg-info">
                                            {{ $note->etudiant->classe->nom }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $note->cours->nom ?? 'N/A' }}
                                    @if($note->cours && $note->cours->code)
                                        <br><small class="text-muted">{{ $note->cours->code }}</small>
                                    @endif
                                </td>
                                <td>
                                    <strong class="{{ $note->note >= 10 ? 'text-success' : 'text-danger' }}" style="font-size: 1.1rem;">
                                        {{ number_format($note->note, 2) }}/20
                                    </strong>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $note->type_evaluation ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    @if($note->semestre)
                                        <span class="badge bg-primary">{{ $note->semestre }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $note->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('enseignant.notes.edit', $note) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                            ✏️
                                        </a>
                                        <form action="{{ route('enseignant.notes.destroy', $note) }}" method="POST" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette note ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                                🗑️
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $notes->links() }}
            </div>
        </div>
    </div>
@else
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <p class="text-muted mb-3">
                @if($classeId || $coursId)
                    Aucune note trouvée avec les filtres sélectionnés.
                @else
                    Aucune note enregistrée.
                @endif
            </p>
            <a href="{{ route('enseignant.notes.create') }}" class="btn btn-primary">➕ Saisir des notes</a>
            @if($classeId || $coursId)
                <a href="{{ route('enseignant.notes.index') }}" class="btn btn-outline-secondary ms-2">Réinitialiser les filtres</a>
            @endif
        </div>
    </div>
@endif

<script>
function updateCoursList() {
    const classeId = document.getElementById('classe_id').value;
    const coursSelect = document.getElementById('cours_id');
    
    // Réinitialiser le select des cours
    coursSelect.innerHTML = '<option value="">Chargement...</option>';
    coursSelect.disabled = true;
    
    if (!classeId) {
        coursSelect.innerHTML = '<option value="">Tous les cours</option>';
        coursSelect.disabled = true;
        return;
    }
    
    // Charger les cours pour cette classe
    fetch(`/enseignant/classes/${classeId}/cours-notes`)
        .then(response => response.json())
        .then(data => {
            coursSelect.innerHTML = '<option value="">Tous les cours</option>';
            if (data.length > 0) {
                data.forEach(cours => {
                    coursSelect.innerHTML += `<option value="${cours.id}">${cours.nom}${cours.code ? ' (' + cours.code + ')' : ''}</option>`;
                });
                coursSelect.disabled = false;
            } else {
                coursSelect.innerHTML = '<option value="">Aucun cours disponible</option>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            coursSelect.innerHTML = '<option value="">Erreur de chargement</option>';
        });
}

// Initialiser au chargement si une classe est déjà sélectionnée
document.addEventListener('DOMContentLoaded', function() {
    @if($classeId)
        updateCoursList();
        // Sélectionner le cours si déjà filtré
        setTimeout(() => {
            const coursSelect = document.getElementById('cours_id');
            if (coursSelect && '{{ $coursId }}') {
                coursSelect.value = '{{ $coursId }}';
            }
        }, 500);
    @endif
});
</script>
@endsection


