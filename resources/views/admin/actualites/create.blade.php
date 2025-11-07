@extends('layouts.admin')

@section('title', 'Nouvelle Actualité')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>📰 Nouvelle Actualité</h1>
    <a href="{{ route('admin.actualites.index') }}" class="btn btn-secondary">
        ← Retour
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.actualites.store') }}" method="POST" enctype="multipart/form-data">
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
                <label for="description" class="form-label">Description *</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                <small class="text-muted">Description courte qui apparaîtra dans les listes</small>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="contenu" class="form-label">Contenu *</label>
                <textarea class="form-control @error('contenu') is-invalid @enderror" 
                          id="contenu" name="contenu" rows="10" required>{{ old('contenu') }}</textarea>
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
                        <option value="general" {{ old('categorie') == 'general' ? 'selected' : '' }}>Général</option>
                        <option value="academique" {{ old('categorie') == 'academique' ? 'selected' : '' }}>Académique</option>
                        <option value="evenement" {{ old('categorie') == 'evenement' ? 'selected' : '' }}>Événement</option>
                        <option value="admission" {{ old('categorie') == 'admission' ? 'selected' : '' }}>Admission</option>
                        <option value="vie-etudiante" {{ old('categorie') == 'vie-etudiante' ? 'selected' : '' }}>Vie Étudiante</option>
                    </select>
                    @error('categorie')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="date_publication" class="form-label">Date de Publication *</label>
                    <input type="date" class="form-control @error('date_publication') is-invalid @enderror" 
                           id="date_publication" name="date_publication" 
                           value="{{ old('date_publication', date('Y-m-d')) }}" required>
                    @error('date_publication')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                       id="image" name="image" accept="image/*">
                <small class="text-muted">Format: JPG, PNG, GIF. Max: 2MB</small>
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="publie" name="publie" 
                       {{ old('publie', true) ? 'checked' : '' }}>
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
<script src="https://cdn.tiny.cloud/1/{{ config('app.tinymce_api_key') }}/tinymce/6/tinymce.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('contenu');
    if (!textarea) return;
    
    let imageModal = null;
    let selectedImageUrl = null;
    
    function openImageManager(editor) {
        selectedImageUrl = null;
        loadImageGrid();
        const modalEl = document.getElementById('imageManagerModal');
        if (!imageModal) {
            imageModal = new bootstrap.Modal(modalEl);
        }
        imageModal.show();
        
        modalEl.addEventListener('hidden.bs.modal', function() {
            if (selectedImageUrl && editor) {
                editor.insertContent('<img src="' + selectedImageUrl + '" alt="" class="img-fluid" />');
            }
        }, { once: true });
    }
    
    function loadImageGrid() {
        const grid = document.getElementById('imageGrid');
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
                        col.innerHTML = '<div class="card" style="cursor:pointer;border:2px solid #ddd;" onclick="window.selectImage(\'' + file.url + '\')"><img src="' + file.url + '" class="card-img-top" style="height:100px;object-fit:cover;"><div class="card-body p-2"><small class="text-truncate d-block">' + file.name + '</small></div></div>';
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
    
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '#contenu',
            height: 500,
            menubar: true,
            plugins: 'lists link image table code',
            toolbar: 'undo redo | styles | bold italic underline | bullist numlist | link image table | code | myimage',
            language: 'fr_FR',
            setup: function(editor) {
                editor.ui.registry.addButton('myimage', {
                    text: '📷 Images',
                    tooltip: 'Gérer les images',
                    onAction: function() {
                        openImageManager(editor);
                    }
                });
            },
            images_upload_handler: function(blobInfo, success, failure) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', "{{ route('admin.media.upload') }}");
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            const data = JSON.parse(xhr.responseText);
                            if (data.success && data.file && data.file.url) {
                                success(data.file.url);
                            } else {
                                failure('Erreur upload');
                            }
                        } catch(e) {
                            failure('Erreur JSON');
                        }
                    } else {
                        failure('Erreur ' + xhr.status);
                    }
                };
                xhr.onerror = function() {
                    failure('Erreur réseau');
                };
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                xhr.send(formData);
            }
        });
    } else {
        console.error('TinyMCE not loaded');
    }
});
</script>
@endpush

