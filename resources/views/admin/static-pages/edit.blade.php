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
                        <textarea class="form-control @error('contenu') is-invalid @enderror" 
                                  id="contenu" name="contenu" rows="15" required>{{ old('contenu', $staticPage->contenu) }}</textarea>
                        <small class="text-muted">Utilisez l'éditeur TinyMCE ci-dessus pour formater votre contenu</small>
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

@endsection

@push('scripts')
<script src="https://cdn.tiny.cloud/1/{{ config('app.tinymce_api_key') }}/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
(function(){
    function initTiny(){
        tinymce.init({
            selector: '#contenu',
            height: 500,
            menubar: true,
            plugins: 'lists link image table media code codesample autoresize',
            toolbar: 'undo redo | styles | bold italic underline | bullist numlist | link image media table | alignleft aligncenter alignright | code',
            language: 'fr_FR',
            convert_urls: false,
            images_upload_handler: function (blobInfo, success, failure) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', "{{ route('admin.media.upload') }}");
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.onload = function() {
                    if (xhr.status !== 200) {
                        failure('HTTP Error: ' + xhr.status);
                        return;
                    }
                    let json;
                    try { 
                        json = JSON.parse(xhr.responseText); 
                    } catch (e) { 
                        failure('Invalid JSON: ' + e.message); 
                        return; 
                    }
                    if (!json || !json.success || !json.file || !json.file.url) {
                        failure('Upload failed: ' + (json.message || 'Unknown error'));
                        return;
                    }
                    success(json.file.url);
                };
                xhr.onerror = function() {
                    failure('Network error during upload');
                };
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                xhr.send(formData);
            },
            file_picker_types: 'image',
            file_picker_callback: function(callback, value, meta) {
                if (meta.filetype === 'image') {
                    // Afficher les images existantes
                    fetch("{{ route('admin.media.images') }}", {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(r => {
                        if (!r.ok) throw new Error('HTTP ' + r.status);
                        return r.json();
                    })
                    .then(data => {
                        const files = (data && data.files) ? data.files : [];
                        let html = '<div style="padding:1rem;max-height:400px;overflow-y:auto;">';
                        if (files.length > 0) {
                            html += '<div style="display:flex;flex-wrap:wrap;gap:10px;">';
                            files.forEach(f => {
                                html += '<div style="position:relative;cursor:pointer;border:2px solid #ddd;border-radius:8px;overflow:hidden;transition:border-color 0.2s;" onmouseover="this.style.borderColor=\'#007bff\'" onmouseout="this.style.borderColor=\'#ddd\'" onclick="window.selectedImageUrl=\''+f.url+'\';window.selectedImageCallback('+JSON.stringify(f.url)+');">';
                                html += '<img src="'+f.url+'" style="width:120px;height:120px;object-fit:cover;display:block;" />';
                                html += '<div style="position:absolute;bottom:0;left:0;right:0;background:rgba(0,0,0,0.7);color:white;padding:4px;font-size:11px;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;">'+f.name+'</div>';
                                html += '</div>';
                            });
                            html += '</div>';
                        } else {
                            html += '<div style="text-align:center;padding:2rem;color:#999;">Aucune image trouvée</div>';
                        }
                        html += '</div>';
                        
                        const win = tinymce.activeEditor.windowManager.open({
                            title: 'Sélectionner une image',
                            body: {
                                type: 'panel',
                                items: [{
                                    type: 'htmlpanel',
                                    html: html
                                }]
                            },
                            buttons: [
                                {
                                    type: 'custom',
                                    text: 'Uploader une nouvelle image',
                                    primary: true,
                                    onclick: function() {
                                        const input = document.createElement('input');
                                        input.type = 'file';
                                        input.accept = 'image/*';
                                        input.onchange = function() {
                                            const file = this.files[0];
                                            if (!file) return;
                                            const formData = new FormData();
                                            formData.append('_token', '{{ csrf_token() }}');
                                            formData.append('file', file);
                                            fetch("{{ route('admin.media.upload') }}", {
                                                method: 'POST',
                                                body: formData,
                                                headers: { 'X-Requested-With': 'XMLHttpRequest' }
                                            })
                                            .then(r => r.json())
                                            .then(data => {
                                                if (data.success && data.file && data.file.url) {
                                                    callback(data.file.url, {alt: file.name});
                                                    win.close();
                                                } else {
                                                    alert('Erreur lors de l\'upload');
                                                }
                                            })
                                            .catch(() => alert('Erreur réseau'));
                                        };
                                        input.click();
                                    }
                                },
                                {
                                    type: 'cancel',
                                    text: 'Fermer'
                                }
                            ]
                        });
                        
                        // Stocker le callback globalement pour les clics
                        window.selectedImageCallback = function(url) {
                            callback(url, {alt: ''});
                            win.close();
                        };
                    })
                    .catch(err => {
                        console.error('Error loading images:', err);
                        // Fallback: ouvrir un input file local
                        const input = document.createElement('input');
                        input.type = 'file';
                        input.accept = 'image/*';
                        input.onchange = function() {
                            const file = this.files[0];
                            if (!file) return;
                            const reader = new FileReader();
                            reader.onload = function() {
                                callback(reader.result, {alt: file.name});
                            };
                            reader.readAsDataURL(file);
                        };
                        input.click();
                    });
                }
            }
        });
    }
    if (window.tinymce) initTiny(); else document.addEventListener('DOMContentLoaded', initTiny);
})();
</script>
@endpush

