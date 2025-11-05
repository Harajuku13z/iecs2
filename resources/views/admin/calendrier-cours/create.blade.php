@extends('layouts.admin')

@section('title', 'Ajouter un Cours au Calendrier')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>➕ Ajouter un Cours au Calendrier</h1>
    <a href="{{ route('admin.calendrier-cours.index') }}" class="btn btn-secondary">
        ← Retour
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.calendrier-cours.store') }}" method="POST" id="calForm">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="classe_id" class="form-label">Classe *</label>
                    <select class="form-select @error('classe_id') is-invalid @enderror" id="classe_id" name="classe_id" required>
                        <option value="">Sélectionner une classe</option>
                        @foreach($classes as $classe)
                            <option value="{{ $classe->id }}" {{ old('classe_id') == $classe->id ? 'selected' : '' }}>
                                {{ $classe->nom }} - {{ optional($classe->filiere)->nom }} ({{ optional($classe->niveau)->nom }})
                            </option>
                        @endforeach
                    </select>
                    @error('classe_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="semestre" class="form-label">Semestre *</label>
                    <select class="form-select @error('semestre') is-invalid @enderror" id="semestre" name="semestre" required>
                        <option value="">Sélectionner un semestre</option>
                        <option value="1">Semestre 1</option>
                        <option value="2">Semestre 2</option>
                        <option value="3">Semestre 3</option>
                    </select>
                    @error('semestre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="form-label mb-0">Éléments du calendrier *</label>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="addRow">➕ Ajouter une ligne</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="entriesTable">
                        <thead>
                            <tr>
                                <th style="width:20%">Cours</th>
                                <th style="width:15%">Jour *</th>
                                <th style="width:15%">Début *</th>
                                <th style="width:15%">Fin *</th>
                                <th style="width:15%">Salle</th>
                                <th style="width:15%">Enseignant</th>
                                <th style="width:5%">Action</th>
                            </tr>
                        </thead>
                        <tbody id="entriesBody">
                            <tr class="entry-row">
                                <td>
                                    <select class="form-select form-select-sm" name="entries[0][cours_id]">
                                        <option value="">—</option>
                                        @foreach($cours as $c)
                                            <option value="{{ $c->id }}">{{ $c->nom }} ({{ $c->code }})</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="form-select form-select-sm" name="entries[0][jour_semaine]" required>
                                        <option value="">—</option>
                                        @foreach(['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche'] as $j)
                                            <option value="{{ $j }}">{{ $j }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="time" class="form-control form-control-sm" name="entries[0][heure_debut]" required>
                                </td>
                                <td>
                                    <input type="time" class="form-control form-control-sm" name="entries[0][heure_fin]" required>
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="entries[0][salle]" placeholder="Ex: Salle 101">
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="entries[0][enseignant]" placeholder="Nom">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger removeRow" disabled>🗑️</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
                <a href="{{ route('admin.calendrier-cours.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<script>
let rowIdx = 1;
document.getElementById('addRow').addEventListener('click', function() {
    const tbody = document.getElementById('entriesBody');
    const row = document.createElement('tr');
    row.className = 'entry-row';
    const coursOptions = `@foreach($cours as $c)<option value="{{ $c->id }}">{{ $c->nom }} ({{ $c->code }})</option>@endforeach`;
    const jours = ['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche'];
    row.innerHTML = `
        <td>
            <select class="form-select form-select-sm" name="entries[${rowIdx}][cours_id]">
                <option value="">—</option>
                ${coursOptions}
            </select>
        </td>
        <td>
            <select class="form-select form-select-sm" name="entries[${rowIdx}][jour_semaine]" required>
                <option value="">—</option>
                ${jours.map(j=>`<option value="${j}">${j}</option>`).join('')}
            </select>
        </td>
        <td><input type="time" class="form-control form-control-sm" name="entries[${rowIdx}][heure_debut]" required></td>
        <td><input type="time" class="form-control form-control-sm" name="entries[${rowIdx}][heure_fin]" required></td>
        <td><input type="text" class="form-control form-control-sm" name="entries[${rowIdx}][salle]" placeholder="Ex: Salle 101"></td>
                                <td>
                                    <select class="form-select form-select-sm enseignant-select" name="entries[${rowIdx}][enseignant]" disabled>
                                        <option value="">—</option>
                                    </select>
                                </td>
        <td><button type="button" class="btn btn-sm btn-danger removeRow">🗑️</button></td>
    `;
    tbody.appendChild(row);
    rowIdx++;
    updateButtons();
});

document.addEventListener('click', function(e){
    if (e.target.classList.contains('removeRow')) {
        const rows = document.querySelectorAll('.entry-row');
        if (rows.length > 1) {
            e.target.closest('tr').remove();
            updateButtons();
        }
    }
});

function updateButtons() {
    const rows = document.querySelectorAll('.entry-row');
    rows.forEach((r,i)=>{
        const btn = r.querySelector('.removeRow');
        btn.disabled = rows.length === 1;
    });
}

// Dynamic dependent selects
const classeSelect = document.getElementById('classe_id');
const baseUrl = '{{ url('/admin') }}';

async function fetchCoursForClasse(classeId){
    const res = await fetch(`${baseUrl}/classes/${classeId}/cours-json`);
    if(!res.ok) return [];
    return await res.json();
}

async function fetchEnseignants(coursId, classeId){
    const res = await fetch(`${baseUrl}/cours/${coursId}/enseignants-json?classe_id=${classeId}`);
    if(!res.ok) return [];
    return await res.json();
}

classeSelect.addEventListener('change', async function(){
    const classeId = this.value;
    const cours = classeId ? await fetchCoursForClasse(classeId) : [];
    const rows = document.querySelectorAll('.entry-row');
    rows.forEach(r=>{
        const coursSel = r.querySelector('select[name$="[cours_id]"]');
        coursSel.innerHTML = '<option value="">—</option>' + cours.map(c=>`<option value="${c.id}">${c.nom} (${c.code})</option>`).join('');
        const ensSel = r.querySelector('.enseignant-select');
        if(ensSel){ ensSel.innerHTML = '<option value="">—</option>'; ensSel.disabled = true; }
    });
});

document.addEventListener('change', async function(e){
    if(e.target.matches('select[name$="[cours_id]"]')){
        const row = e.target.closest('.entry-row');
        const ensSel = row.querySelector('.enseignant-select');
        const classeId = classeSelect.value;
        const coursId = e.target.value;
        if(classeId && coursId){
            const enseignants = await fetchEnseignants(coursId, classeId);
            ensSel.innerHTML = '<option value="">—</option>' + enseignants.map(u=>`<option value="${u.name}">${u.name}</option>`).join('');
            ensSel.disabled = false;
        } else {
            ensSel.innerHTML = '<option value="">—</option>';
            ensSel.disabled = true;
        }
    }
});
</script>
@endsection



