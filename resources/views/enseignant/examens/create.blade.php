@extends('layouts.enseignant')

@section('teacher_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>📝 Créer un Examen</h3>
    <a href="{{ route('enseignant.examens.index') }}" class="btn btn-sm btn-outline-secondary">← Retour</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('enseignant.examens.store') }}" method="POST" id="examenForm">
            @csrf
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="cours_id" class="form-label">Cours *</label>
                    <select class="form-select @error('cours_id') is-invalid @enderror" id="cours_id" name="cours_id" required>
                        <option value="">Sélectionner un cours</option>
                        @foreach($cours as $c)
                            <option value="{{ $c->id }}" {{ old('cours_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->nom }} ({{ $c->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('cours_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="classe_id" class="form-label">Classe *</label>
                    <select class="form-select @error('classe_id') is-invalid @enderror" id="classe_id" name="classe_id" required>
                        <option value="">Sélectionner d'abord un cours</option>
                    </select>
                    @error('classe_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="type_evaluation" class="form-label">Type d'examen *</label>
                    <input type="text" class="form-control @error('type_evaluation') is-invalid @enderror" 
                           id="type_evaluation" name="type_evaluation" value="{{ old('type_evaluation') }}" 
                           placeholder="Ex: Examen Final, Partiel, etc." required>
                    @error('type_evaluation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4">
                    <label for="semestre" class="form-label">Semestre</label>
                    <input type="text" class="form-control @error('semestre') is-invalid @enderror" 
                           id="semestre" name="semestre" value="{{ old('semestre') }}" 
                           placeholder="Ex: S1, S2">
                    @error('semestre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4">
                    <label for="date_examen" class="form-label">Date de l'examen</label>
                    <input type="date" class="form-control @error('date_examen') is-invalid @enderror" 
                           id="date_examen" name="date_examen" value="{{ old('date_examen') }}">
                    @error('date_examen')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div id="etudiantsContainer" class="mb-3" style="display: none;">
                <h5 class="mb-3">Notes des Étudiants</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Étudiant</th>
                                <th style="width: 150px;">Note (/20)</th>
                            </tr>
                        </thead>
                        <tbody id="etudiantsList">
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary" id="submitBtn" disabled>💾 Enregistrer l'examen</button>
                <a href="{{ route('enseignant.examens.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<script>
// Utiliser le même script que pour les notes
document.getElementById('cours_id').addEventListener('change', function() {
    const coursId = this.value;
    const classeSelect = document.getElementById('classe_id');
    const etudiantsContainer = document.getElementById('etudiantsContainer');
    const etudiantsList = document.getElementById('etudiantsList');
    const submitBtn = document.getElementById('submitBtn');
    
    classeSelect.innerHTML = '<option value="">Chargement...</option>';
    etudiantsContainer.style.display = 'none';
    submitBtn.disabled = true;
    
    if (!coursId) {
        classeSelect.innerHTML = '<option value="">Sélectionner d\'abord un cours</option>';
        return;
    }
    
    fetch(`/enseignant/cours/${coursId}/classes`)
        .then(response => response.json())
        .then(data => {
            classeSelect.innerHTML = '<option value="">Sélectionner une classe</option>';
            data.forEach(classe => {
                classeSelect.innerHTML += `<option value="${classe.id}">${classe.nom} - ${classe.filiere} (${classe.niveau})</option>`;
            });
        })
        .catch(error => console.error('Error:', error));
});

document.getElementById('classe_id').addEventListener('change', function() {
    const classeId = this.value;
    const coursId = document.getElementById('cours_id').value;
    const etudiantsContainer = document.getElementById('etudiantsContainer');
    const etudiantsList = document.getElementById('etudiantsList');
    const submitBtn = document.getElementById('submitBtn');
    
    if (!classeId || !coursId) {
        etudiantsContainer.style.display = 'none';
        submitBtn.disabled = true;
        return;
    }
    
    fetch(`/enseignant/classes/${classeId}/etudiants`)
        .then(response => response.json())
        .then(data => {
            etudiantsList.innerHTML = '';
            let index = 0;
            data.forEach(etudiant => {
                etudiantsList.innerHTML += `
                    <tr>
                        <td>${etudiant.name}</td>
                        <td>
                            <input type="hidden" name="etudiants[${index}][user_id]" value="${etudiant.id}">
                            <input type="number" class="form-control" name="etudiants[${index}][note]" 
                                   min="0" max="20" step="0.01" required>
                        </td>
                    </tr>
                `;
                index++;
            });
            etudiantsContainer.style.display = 'block';
            submitBtn.disabled = false;
        })
        .catch(error => console.error('Error:', error));
});
</script>
@endsection

