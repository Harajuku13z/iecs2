@extends('layouts.enseignant')

@section('teacher_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>➕ Ajouter une Ressource</h3>
    <a href="{{ route('enseignant.ressources.index') }}" class="btn btn-sm btn-outline-secondary">← Retour</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('enseignant.ressources.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-3">
                <label for="titre" class="form-label">Titre *</label>
                <input type="text" class="form-control @error('titre') is-invalid @enderror" 
                       id="titre" name="titre" value="{{ old('titre') }}" required>
                @error('titre')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="3">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="type" class="form-label">Type *</label>
                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                        <option value="">Sélectionner un type</option>
                        <option value="pdf" {{ old('type') == 'pdf' ? 'selected' : '' }}>PDF</option>
                        <option value="word" {{ old('type') == 'word' ? 'selected' : '' }}>Document Word</option>
                        <option value="image" {{ old('type') == 'image' ? 'selected' : '' }}>Image</option>
                        <option value="video" {{ old('type') == 'video' ? 'selected' : '' }}>Vidéo</option>
                        <option value="lien" {{ old('type') == 'lien' ? 'selected' : '' }}>Lien</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="cours_id" class="form-label">Cours *</label>
                    <select class="form-select @error('cours_id') is-invalid @enderror" id="cours_id" name="cours_id" required>
                        <option value="">Sélectionner un cours</option>
                        @foreach($cours as $c)
                            <option value="{{ $c->id }}" data-classes="{{ $c->classes->pluck('id')->toJson() }}" {{ old('cours_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->nom }} ({{ $c->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('cours_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mb-3" id="classesContainer">
                <label class="form-label">Classes * (sélection multiple)</label>
                <div id="classesList" class="border rounded p-3" style="max-height:200px; overflow-y:auto;">
                    <small class="text-muted">Sélectionnez d'abord un cours</small>
                </div>
                @error('classes_ids')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3" id="fileContainer" style="display:none;">
                <label for="contenu" class="form-label">Fichier *</label>
                <input type="file" class="form-control @error('contenu') is-invalid @enderror" 
                       id="contenu" name="contenu">
                <small class="form-text text-muted" id="fileHint"></small>
                @error('contenu')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3" id="lienContainer" style="display:none;">
                <label for="lien" class="form-label">URL *</label>
                <input type="url" class="form-control @error('lien') is-invalid @enderror" 
                       id="lien" name="lien" value="{{ old('lien') }}" placeholder="https://...">
                @error('lien')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
                <a href="{{ route('enseignant.ressources.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<script>
const coursSelect = document.getElementById('cours_id');
const typeSelect = document.getElementById('type');
const classesContainer = document.getElementById('classesList');
const fileContainer = document.getElementById('fileContainer');
const lienContainer = document.getElementById('lienContainer');
const fileInput = document.getElementById('contenu');
const fileHint = document.getElementById('fileHint');

// Stocker les classes de chaque cours
const coursClasses = {};
@foreach($cours as $c)
    coursClasses[{{ $c->id }}] = @json($c->classes->map(fn($cl) => ['id' => $cl->id, 'nom' => $cl->nom, 'filiere' => $cl->filiere->nom ?? '', 'niveau' => $cl->niveau->nom ?? '']));
@endforeach

// Gérer la sélection du cours
coursSelect.addEventListener('change', function() {
    const coursId = parseInt(this.value);
    classesContainer.innerHTML = '';
    
    if (!coursId || !coursClasses[coursId]) {
        classesContainer.innerHTML = '<small class="text-muted">Sélectionnez d\'abord un cours</small>';
        return;
    }
    
    const classes = coursClasses[coursId];
    if (classes.length === 0) {
        classesContainer.innerHTML = '<small class="text-muted">Aucune classe associée à ce cours</small>';
        return;
    }
    
    classes.forEach(classe => {
        const div = document.createElement('div');
        div.className = 'form-check';
        div.innerHTML = `
            <input class="form-check-input" type="checkbox" name="classes_ids[]" value="${classe.id}" id="classe_${classe.id}">
            <label class="form-check-label" for="classe_${classe.id}">
                ${classe.nom} - ${classe.filiere} (${classe.niveau})
            </label>
        `;
        classesContainer.appendChild(div);
    });
});

// Gérer le type de ressource
typeSelect.addEventListener('change', function() {
    const type = this.value;
    
    fileContainer.style.display = 'none';
    lienContainer.style.display = 'none';
    fileInput.required = false;
    document.getElementById('lien').required = false;
    
    if (type === 'lien') {
        lienContainer.style.display = 'block';
        document.getElementById('lien').required = true;
    } else if (type) {
        fileContainer.style.display = 'block';
        fileInput.required = true;
        
        let accept = '';
        let hint = '';
        if (type === 'pdf') {
            accept = '.pdf';
            hint = 'PDF (max 20MB)';
        } else if (type === 'word') {
            accept = '.doc,.docx';
            hint = 'Word DOC/DOCX (max 20MB)';
        } else if (type === 'image') {
            accept = '.jpg,.jpeg,.png,.gif';
            hint = 'Images JPG, PNG, GIF (max 20MB)';
        } else if (type === 'video') {
            accept = '.mp4,.avi';
            hint = 'Vidéos MP4, AVI (max 20MB)';
        }
        fileInput.accept = accept;
        fileHint.textContent = hint;
    }
});

// Initialiser au chargement
if (coursSelect.value) {
    coursSelect.dispatchEvent(new Event('change'));
}
if (typeSelect.value) {
    typeSelect.dispatchEvent(new Event('change'));
}
</script>
@endsection


