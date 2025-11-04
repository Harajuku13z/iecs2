@extends('layouts.admin')

@section('title', 'Modifier Filière')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Modifier Filière</h1>
    <a href="{{ route('admin.filieres.index') }}" class="btn btn-secondary">
        ← Retour
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.filieres.update', $filiere) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nom" class="form-label">Nom de la filière *</label>
                <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                       id="nom" name="nom" value="{{ old('nom', $filiere->nom) }}" required>
                @error('nom')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="4">{{ old('description', $filiere->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Photo de la filière</label>
                @if($filiere->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $filiere->image) }}" alt="{{ $filiere->nom }}" 
                             style="max-width: 300px; max-height: 200px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd;">
                        <p class="text-muted mt-1"><small>Image actuelle</small></p>
                    </div>
                @endif
                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                       id="image" name="image" accept="image/*">
                <small class="text-muted">Format: JPG, PNG, GIF. Max: 2MB. Laisser vide pour conserver l'image actuelle.</small>
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Spécialités -->
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <label class="form-label mb-0">🎯 Spécialités de cette filière</label>
                    <button type="button" class="btn btn-sm btn-success" onclick="addSpecialite()">
                        ➕ Ajouter une spécialité
                    </button>
                </div>
                <div id="specialites-container">
                    @foreach($filiere->specialites as $specialite)
                        <div class="border rounded p-3 mb-3 specialite-item">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <strong>Spécialité #{{ $loop->iteration }}</strong>
                                <button type="button" class="btn btn-sm btn-danger" onclick="removeSpecialite(this)">
                                    🗑️ Supprimer
                                </button>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Nom de la spécialité *</label>
                                    <input type="text" class="form-control" 
                                           name="specialites[{{ $loop->index }}][nom]" 
                                           value="{{ $specialite->nom }}" 
                                           placeholder="Ex: Réseaux et télécommunications" required>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Description</label>
                                    <input type="text" class="form-control" 
                                           name="specialites[{{ $loop->index }}][description]" 
                                           value="{{ $specialite->description }}" 
                                           placeholder="Description de la spécialité">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <small class="text-muted">Une filière peut avoir plusieurs spécialités</small>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
                <a href="{{ route('admin.filieres.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<script>
let specialiteIndex = {{ $filiere->specialites->count() }};

function addSpecialite(nom = '', description = '') {
    const container = document.getElementById('specialites-container');
    const specialiteDiv = document.createElement('div');
    specialiteDiv.className = 'border rounded p-3 mb-3 specialite-item';
    specialiteDiv.innerHTML = `
        <div class="d-flex justify-content-between align-items-start mb-2">
            <strong>Spécialité #${container.children.length + 1}</strong>
            <button type="button" class="btn btn-sm btn-danger" onclick="removeSpecialite(this)">
                🗑️ Supprimer
            </button>
        </div>
        <div class="row">
            <div class="col-md-6 mb-2">
                <label class="form-label">Nom de la spécialité *</label>
                <input type="text" class="form-control" 
                       name="specialites[${specialiteIndex}][nom]" 
                       value="${nom}" 
                       placeholder="Ex: Réseaux et télécommunications" required>
            </div>
            <div class="col-md-6 mb-2">
                <label class="form-label">Description</label>
                <input type="text" class="form-control" 
                       name="specialites[${specialiteIndex}][description]" 
                       value="${description}" 
                       placeholder="Description de la spécialité">
            </div>
        </div>
    `;
    container.appendChild(specialiteDiv);
    specialiteIndex++;
    updateSpecialiteNumbers();
}

function removeSpecialite(button) {
    button.closest('.specialite-item').remove();
    updateSpecialiteNumbers();
}

function updateSpecialiteNumbers() {
    const items = document.querySelectorAll('.specialite-item');
    items.forEach((item, index) => {
        const strong = item.querySelector('strong');
        if (strong) {
            strong.textContent = `Spécialité #${index + 1}`;
        }
    });
}
</script>
@endsection

