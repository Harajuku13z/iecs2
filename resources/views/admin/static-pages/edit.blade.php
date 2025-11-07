@extends('layouts.admin')

@section('title', 'Modifier une Page Statique')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>📄 Modifier la Page Statique</h1>
    <a href="{{ route('admin.static-pages.index') }}" class="btn btn-secondary">
        ← Retour
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.static-pages.update', $staticPage) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Le formulaire contient des erreurs.</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-md-8">
                    <!-- Titre -->
                    <div class="mb-3">
                        <label for="titre" class="form-label">Titre de la page *</label>
                        <input type="text" class="form-control @error('titre') is-invalid @enderror" 
                               id="titre" name="titre" value="{{ old('titre', $staticPage->titre) }}" required>
                        @error('titre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description courte -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description courte (optionnel)</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="2">{{ old('description', $staticPage->description) }}</textarea>
                        <small class="text-muted">Description affichée dans les listes et les aperçus</small>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Type de contenu -->
                    <div class="mb-3">
                        <label class="form-label">Type de contenu *</label>
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" name="type_contenu" id="type_texte" value="texte" 
                                   {{ old('type_contenu', $staticPage->type_contenu) === 'texte' ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary" for="type_texte">📝 Texte simple</label>
                            
                            <input type="radio" class="btn-check" name="type_contenu" id="type_html" value="html"
                                   {{ old('type_contenu', $staticPage->type_contenu) === 'html' ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary" for="type_html">🌐 HTML</label>
                        </div>
                    </div>

                    <!-- Contenu -->
                    <div class="mb-3">
                        <label for="contenu" class="form-label">Contenu *</label>
                        <div id="editor-container" style="height: 400px; background: white; border: 1px solid #ced4da; border-radius: 0.375rem;"></div>
                        <textarea class="form-control @error('contenu') is-invalid @enderror d-none" 
                                  id="contenu" name="contenu" required>{{ old('contenu', $staticPage->contenu) }}</textarea>
                        <small class="text-muted">Utilisez l'éditeur ci-dessus pour formater votre contenu</small>
                        @error('contenu')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Image principale -->
                    <div class="mb-3">
                        <label for="image_principale" class="form-label">Image de mise en avant</label>
                        @if($staticPage->image_principale)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $staticPage->image_principale) }}" 
                                     alt="Image actuelle" 
                                     class="img-thumbnail" 
                                     style="max-width: 100%;">
                                <p class="small text-muted mt-1">Image actuelle</p>
                            </div>
                        @endif
                        <input type="file" class="form-control @error('image_principale') is-invalid @enderror" 
                               id="image_principale" name="image_principale" accept="image/*">
                        <small class="text-muted">Format: JPG, PNG, GIF, WEBP (max 5MB). Laissez vide pour conserver l'image actuelle.</small>
                        <div id="image-preview" class="mt-2" style="display: none;">
                            <img id="preview-img" src="" alt="Aperçu" class="img-thumbnail" style="max-width: 100%;">
                            <p class="small text-muted mt-1">Nouvelle image</p>
                        </div>
                        @error('image_principale')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Menu -->
                    <div class="mb-3">
                        <label for="menu_nom" class="form-label">Nom dans le menu</label>
                        <input type="text" class="form-control" id="menu_nom" name="menu_nom" 
                               value="{{ old('menu_nom', $staticPage->menu_nom) }}" placeholder="Laissez vide pour utiliser le titre">
                        <small class="text-muted">Nom affiché dans le menu de navigation</small>
                    </div>

                    <div class="mb-3">
                        <label for="menu_parent" class="form-label">Menu parent (sous-menu)</label>
                        <select class="form-select" id="menu_parent" name="menu_parent">
                            <option value="">Aucun (menu principal)</option>
                            @foreach($mainMenuItems as $item)
                                @if($item->id !== $staticPage->id)
                                    <option value="{{ $item->menu_nom }}" {{ old('menu_parent', $staticPage->menu_parent) === $item->menu_nom ? 'selected' : '' }}>
                                        {{ $item->menu_nom }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        <small class="text-muted">Sélectionnez un menu parent pour créer un sous-menu</small>
                    </div>

                    <div class="mb-3">
                        <label for="menu_ordre" class="form-label">Ordre dans le menu</label>
                        <input type="number" class="form-control" id="menu_ordre" name="menu_ordre" 
                               value="{{ old('menu_ordre', $staticPage->menu_ordre) }}" min="0">
                        <small class="text-muted">Ordre d'affichage (0 = premier)</small>
                    </div>

                    <!-- Options -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="hidden" name="afficher_menu" value="0">
                            <input class="form-check-input" type="checkbox" id="afficher_menu" name="afficher_menu" value="1"
                                   {{ old('afficher_menu', $staticPage->afficher_menu) ? 'checked' : '' }}>
                            <label class="form-check-label" for="afficher_menu">
                                Afficher dans le menu
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="hidden" name="publie" value="0">
                            <input class="form-check-input" type="checkbox" id="publie" name="publie" value="1"
                                   {{ old('publie', $staticPage->publie) ? 'checked' : '' }}>
                            <label class="form-check-label" for="publie">
                                Publier immédiatement
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">💾 Enregistrer les modifications</button>
                <a href="{{ route('admin.static-pages.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<script>
// Même script que dans create.blade.php
document.querySelectorAll('input[name="type_contenu"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const toolbar = document.getElementById('editor-toolbar');
        const texteHint = document.getElementById('type_texte_hint');
        const htmlHint = document.getElementById('type_html_hint');
        
        if (this.value === 'html') {
            toolbar.style.display = 'block';
            texteHint.style.display = 'none';
            htmlHint.style.display = 'inline';
        } else {
            toolbar.style.display = 'block';
            texteHint.style.display = 'inline';
            htmlHint.style.display = 'none';
        }
    });
});

document.getElementById('image_principale').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('image-preview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});

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
    
    setTimeout(function() {
        const toolbarEl = container.parentElement.querySelector('.ql-toolbar');
        if (toolbarEl) {
            const imageButton = toolbarEl.querySelector('.ql-image');
            if (imageButton) {
                imageButton.onmousedown = function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    openImageManager();
                    return false;
                };
            }
        }
    }, 200);
    
    quill.root.innerHTML = textarea.value || '';
    
    quill.on('text-change', function() {
        textarea.value = quill.root.innerHTML;
    });
    
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

