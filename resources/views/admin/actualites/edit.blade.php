@extends('layouts.admin')

@section('title', 'Modifier Actualité')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>📰 Modifier Actualité</h1>
    <a href="{{ route('admin.actualites.index') }}" class="btn btn-secondary">
        ← Retour
    </a>
</div>

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>❌ Erreur:</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>❌ Erreurs de validation:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>✅ Succès:</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.actualites.update', $actualite) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="titre" class="form-label">Titre *</label>
                <input type="text" class="form-control @error('titre') is-invalid @enderror" 
                       id="titre" name="titre" value="{{ old('titre', $actualite->titre) }}" required>
                @error('titre')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description *</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="3" required>{{ old('description', $actualite->description) }}</textarea>
                <small class="text-muted">Description courte qui apparaîtra dans les listes</small>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="contenu" class="form-label">Contenu *</label>
                <div id="editor-container" style="height: 400px; background: white; border: 1px solid #ced4da; border-radius: 0.375rem;"></div>
                <textarea class="form-control @error('contenu') is-invalid @enderror d-none" 
                          id="contenu" name="contenu" required>{{ old('contenu', $actualite->contenu) }}</textarea>
                <small class="text-muted">Contenu complet de l'actualité</small>
                @error('contenu')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="categorie" class="form-label">Catégorie *</label>
                    <select class="form-select @error('categorie') is-invalid @enderror" 
                            id="categorie" name="categorie" required>
                        <option value="general" {{ old('categorie', $actualite->categorie) == 'general' ? 'selected' : '' }}>Général</option>
                        <option value="academique" {{ old('categorie', $actualite->categorie) == 'academique' ? 'selected' : '' }}>Académique</option>
                        <option value="evenement" {{ old('categorie', $actualite->categorie) == 'evenement' ? 'selected' : '' }}>Événement</option>
                        <option value="admission" {{ old('categorie', $actualite->categorie) == 'admission' ? 'selected' : '' }}>Admission</option>
                        <option value="vie-etudiante" {{ old('categorie', $actualite->categorie) == 'vie-etudiante' ? 'selected' : '' }}>Vie Étudiante</option>
                    </select>
                    @error('categorie')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="date_publication" class="form-label">Date de Publication</label>
                    <input type="date" class="form-control @error('date_publication') is-invalid @enderror" 
                           id="date_publication" name="date_publication" 
                           value="{{ old('date_publication', optional($actualite->date_publication)->format('Y-m-d') ?? date('Y-m-d')) }}">
                    <small class="text-muted">Laisser vide pour utiliser la date actuelle</small>
                    @error('date_publication')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                @if($actualite->image)
                    <div class="mb-3">
                        <p class="text-muted small mb-2"><strong>Image actuelle:</strong></p>
                        <img src="{{ asset('storage/' . $actualite->image) }}" alt="{{ $actualite->titre }}" 
                             style="max-width: 100%; max-height: 300px; object-fit: cover; border-radius: 8px; border: 2px solid #dee2e6;"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <div style="display:none; padding: 1rem; background: #f8f9fa; border-radius: 8px; color: #dc3545;">
                            <small>⚠️ Image non trouvée. Veuillez re-uploader l'image.</small>
                        </div>
                        <p class="text-muted small mt-2">Fichier: {{ $actualite->image }}</p>
                    </div>
                @else
                    <div class="mb-2">
                        <small class="text-muted">Aucune image actuellement définie.</small>
                    </div>
                @endif
                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                       id="image" name="image" accept="image/*">
                <small class="text-muted">Formats acceptés: JPG, PNG, GIF, WebP. Taille max: 5MB. Laisser vide pour conserver l'image actuelle.</small>
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="publie" name="publie" 
                       {{ old('publie', $actualite->publie) ? 'checked' : '' }}>
                <label class="form-check-label" for="publie">
                    Publier immédiatement
                </label>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
                <a href="{{ route('admin.actualites.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@push('modals')
<div class="modal fade" id="imageManagerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gérer les images</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Uploader une image</label>
                    <input type="file" id="imageUploadInput" class="form-control" accept="image/*">
                    <div id="uploadStatus" class="mt-2"></div>
                </div>
                <hr>
                <div class="mb-2"><strong>Images disponibles :</strong></div>
                <div id="imageGrid" class="row g-2" style="max-height: 400px; overflow-y: auto;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
@endpush

@endsection

@push('scripts')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('editor-container');
    const textarea = document.getElementById('contenu');
    if (!container || !textarea) return;
    
    let imageModal = null;
    let selectedImageUrl = null;
    let quill = null;
    
    quill = new Quill('#editor-container', {
        theme: 'snow',
        modules: {
            toolbar: {
                container: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['link', 'image'],
                    [{ 'align': [] }],
                    ['clean']
                ]
            }
        }
    });
    
    const toolbar = quill.getModule('toolbar');
    toolbar.addHandler('image', function() {
        openImageManager();
    });
    
    quill.root.innerHTML = textarea.value || '';
    
    quill.on('text-change', function() {
        textarea.value = quill.root.innerHTML;
    });
    
    function openImageManager() {
        selectedImageUrl = null;
        loadImageGrid();
        const modalEl = document.getElementById('imageManagerModal');
        if (!imageModal) {
            imageModal = new bootstrap.Modal(modalEl);
        }
        imageModal.show();
        
        modalEl.addEventListener('hidden.bs.modal', function() {
            if (selectedImageUrl && quill) {
                const range = quill.getSelection();
                if (range) {
                    quill.insertEmbed(range.index, 'image', selectedImageUrl, 'user');
                } else {
                    quill.insertEmbed(0, 'image', selectedImageUrl, 'user');
                }
            }
        }, { once: true });
    }
    
    function loadImageGrid() {
        const grid = document.getElementById('imageGrid');
        if (!grid) return;
        grid.innerHTML = '<div class="col-12 text-center py-3">Chargement...</div>';
        
        const xhr = new XMLHttpRequest();
        xhr.open('GET', "{{ route('admin.media.images') }}");
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onload = function() {
            if (xhr.status === 200) {
                try {
                    const data = JSON.parse(xhr.responseText);
                    const files = data.files || [];
                    grid.innerHTML = '';
                    
                    if (files.length === 0) {
                        grid.innerHTML = '<div class="col-12 text-center py-3 text-muted">Aucune image</div>';
                        return;
                    }
                    
                    files.forEach(function(file) {
                        const col = document.createElement('div');
                        col.className = 'col-6 col-md-4 col-lg-3';
                        col.innerHTML = '<div class="card" style="cursor:pointer;border:2px solid #ddd;" onclick="window.selectImage(\'' + file.url.replace(/'/g, "\\'") + '\')"><img src="' + file.url + '" class="card-img-top" style="height:100px;object-fit:cover;"><div class="card-body p-2"><small class="text-truncate d-block">' + file.name + '</small></div></div>';
                        grid.appendChild(col);
                    });
                } catch(e) {
                    grid.innerHTML = '<div class="col-12 text-center py-3 text-danger">Erreur</div>';
                }
            }
        };
        xhr.send();
    }
    
    window.selectImage = function(url) {
        selectedImageUrl = url;
        if (imageModal) imageModal.hide();
    };
    
    const uploadInput = document.getElementById('imageUploadInput');
    const uploadStatus = document.getElementById('uploadStatus');
    
    if (uploadInput) {
        uploadInput.addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;
            
            uploadStatus.innerHTML = '<div class="text-info">Upload...</div>';
            
            const xhr = new XMLHttpRequest();
            xhr.open('POST', "{{ route('admin.media.upload') }}");
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        if (data.success && data.file && data.file.url) {
                            uploadStatus.innerHTML = '<div class="text-success">✓ Uploadé</div>';
                            uploadInput.value = '';
                            loadImageGrid();
                            setTimeout(function() { uploadStatus.innerHTML = ''; }, 2000);
                        } else {
                            uploadStatus.innerHTML = '<div class="text-danger">Erreur</div>';
                        }
                    } catch(e) {
                        uploadStatus.innerHTML = '<div class="text-danger">Erreur</div>';
                    }
                } else {
                    uploadStatus.innerHTML = '<div class="text-danger">Erreur ' + xhr.status + '</div>';
                }
            };
            xhr.onerror = function() {
                uploadStatus.innerHTML = '<div class="text-danger">Erreur réseau</div>';
            };
            
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('file', file);
            xhr.send(formData);
        });
    }
    
    const modalEl = document.getElementById('imageManagerModal');
    if (modalEl) {
        modalEl.addEventListener('show.bs.modal', loadImageGrid);
    }
    
    const form = textarea.closest('form');
    if (form) {
        form.addEventListener('submit', function() {
            textarea.value = quill.root.innerHTML;
        });
    }
});
</script>
@endpush

