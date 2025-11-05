@extends('layouts.admin')

@section('title', 'Nouveau Cours')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>➕ Ajouter des Cours</h1>
    <a href="{{ route('admin.cours.index') }}" class="btn btn-secondary">← Retour</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.cours.store') }}" method="POST" id="coursForm">
            @csrf
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="classe_id" class="form-label">Classe *</label>
                    <select class="form-select @error('classe_id') is-invalid @enderror" 
                            id="classe_id" name="classe_id" required>
                        <option value="">Sélectionner une classe</option>
                        @foreach($classes as $classe)
                            <option value="{{ $classe->id }}" {{ old('classe_id') == $classe->id ? 'selected' : '' }}>
                                {{ $classe->nom }} - {{ $classe->filiere->nom ?? '' }} ({{ $classe->niveau->nom ?? '' }})
                            </option>
                        @endforeach
                    </select>
                    @error('classe_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="semestre" class="form-label">Semestre *</label>
                    <select class="form-select @error('semestre') is-invalid @enderror" 
                            id="semestre" name="semestre" required>
                        <option value="">Sélectionner un semestre</option>
                        <option value="1" {{ old('semestre') == '1' ? 'selected' : '' }}>Semestre 1</option>
                        <option value="2" {{ old('semestre') == '2' ? 'selected' : '' }}>Semestre 2</option>
                        <option value="3" {{ old('semestre') == '3' ? 'selected' : '' }}>Semestre 3</option>
                    </select>
                    @error('semestre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="form-label mb-0">Cours *</label>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="addCoursRow">
                        ➕ Ajouter une ligne
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="coursTable">
                        <thead>
                            <tr>
                                <th style="width:25%">Nom *</th>
                                <th style="width:15%">Code *</th>
                                <th style="width:10%">Coefficient *</th>
                                <th style="width:25%">Description</th>
                                <th style="width:20%">Enseignant</th>
                                <th style="width:5%">Action</th>
                            </tr>
                        </thead>
                        <tbody id="coursRows">
                            <tr class="cours-row">
                                <td>
                                    <input type="text" class="form-control form-control-sm" 
                                           name="cours[0][nom]" required>
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm" 
                                           name="cours[0][code]" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" 
                                           name="cours[0][coefficient]" min="1" value="1" required>
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm" 
                                           name="cours[0][description]" placeholder="Optionnel">
                                </td>
                                <td>
                                    <select class="form-select form-select-sm" name="cours[0][enseignant_id]">
                                        <option value="">Aucun</option>
                                        @foreach($enseignants as $enseignant)
                                            <option value="{{ $enseignant->id }}">{{ $enseignant->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger remove-row" disabled>
                                        🗑️
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @error('cours')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
                <a href="{{ route('admin.cours.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<script>
let rowIndex = 1;

document.getElementById('addCoursRow').addEventListener('click', function() {
    const tbody = document.getElementById('coursRows');
    const newRow = document.createElement('tr');
    newRow.className = 'cours-row';
    
    const enseignantsOptions = `@foreach($enseignants as $enseignant)
                                        <option value="{{ $enseignant->id }}">{{ $enseignant->name }}</option>
                                    @endforeach`;
    
    newRow.innerHTML = `
        <td>
            <input type="text" class="form-control form-control-sm" 
                   name="cours[${rowIndex}][nom]" required>
        </td>
        <td>
            <input type="text" class="form-control form-control-sm" 
                   name="cours[${rowIndex}][code]" required>
        </td>
        <td>
            <input type="number" class="form-control form-control-sm" 
                   name="cours[${rowIndex}][coefficient]" min="1" value="1" required>
        </td>
        <td>
            <input type="text" class="form-control form-control-sm" 
                   name="cours[${rowIndex}][description]" placeholder="Optionnel">
        </td>
        <td>
            <select class="form-select form-select-sm" name="cours[${rowIndex}][enseignant_id]">
                <option value="">Aucun</option>
                ${enseignantsOptions}
            </select>
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-danger remove-row">🗑️</button>
        </td>
    `;
    
    tbody.appendChild(newRow);
    rowIndex++;
    
    updateRemoveButtons();
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-row')) {
        const row = e.target.closest('tr');
        if (document.querySelectorAll('.cours-row').length > 1) {
            row.remove();
            updateRemoveButtons();
        }
    }
});

function updateRemoveButtons() {
    const rows = document.querySelectorAll('.cours-row');
    rows.forEach(row => {
        const btn = row.querySelector('.remove-row');
        btn.disabled = rows.length === 1;
    });
}
</script>
@endsection
