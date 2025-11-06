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
                        <div id="editor-toolbar" style="display: none;" class="mb-2 p-2 bg-light rounded">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText('bold')" title="Gras">
                                <strong>B</strong>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText('italic')" title="Italique">
                                <em>I</em>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText('underline')" title="Souligné">
                                <u>U</u>
                            </button>
                            <span class="mx-2">|</span>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertList('ul')" title="Liste à puces">
                                • Liste
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertList('ol')" title="Liste numérotée">
                                1. Liste
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertLink()" title="Lien">
                                🔗 Lien
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-open-media" title="Médiathèque">
                                🖼️ Média
                            </button>
                        </div>
                        <textarea class="form-control @error('contenu') is-invalid @enderror" 
                                  id="contenu" name="contenu" rows="15" required>{{ old('contenu', $staticPage->contenu) }}</textarea>
                        <small class="text-muted">
                            <span id="type_texte_hint" style="display: none;">
                                Mode texte : Utilisez les boutons ci-dessus pour formater ou saisissez du texte simple.
                            </span>
                            <span id="type_html_hint" style="display: none;">
                                Mode HTML : Vous pouvez utiliser du code HTML directement. Exemples : 
                                <code>&lt;p&gt;Paragraphe&lt;/p&gt;</code>, 
                                <code>&lt;ul&gt;&lt;li&gt;Item&lt;/li&gt;&lt;/ul&gt;</code>,
                                <code>&lt;img src="..." alt="..."&gt;</code>
                            </span>
                        </small>
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

function formatText(command) {
    const textarea = document.getElementById('contenu');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const selectedText = textarea.value.substring(start, end);
    
    let formatted = '';
    if (command === 'bold') {
        formatted = '<strong>' + selectedText + '</strong>';
    } else if (command === 'italic') {
        formatted = '<em>' + selectedText + '</em>';
    } else if (command === 'underline') {
        formatted = '<u>' + selectedText + '</u>';
    }
    
    textarea.value = textarea.value.substring(0, start) + formatted + textarea.value.substring(end);
    textarea.focus();
    textarea.setSelectionRange(start + formatted.length, start + formatted.length);
}

function insertList(type) {
    const textarea = document.getElementById('contenu');
    const start = textarea.selectionStart;
    const cursorPos = start;
    
    let listHtml = '';
    if (type === 'ul') {
        listHtml = '<ul>\n<li>Item 1</li>\n<li>Item 2</li>\n<li>Item 3</li>\n</ul>';
    } else {
        listHtml = '<ol>\n<li>Item 1</li>\n<li>Item 2</li>\n<li>Item 3</li>\n</ol>';
    }
    
    textarea.value = textarea.value.substring(0, cursorPos) + listHtml + textarea.value.substring(cursorPos);
    textarea.focus();
    textarea.setSelectionRange(cursorPos + listHtml.length, cursorPos + listHtml.length);
}

function insertLink() {
    const url = prompt('Entrez l\'URL du lien:');
    if (url) {
        const text = prompt('Texte du lien (optionnel):', url);
        const textarea = document.getElementById('contenu');
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const selectedText = textarea.value.substring(start, end) || text || url;
        
        const linkHtml = '<a href="' + url + '">' + selectedText + '</a>';
        textarea.value = textarea.value.substring(0, start) + linkHtml + textarea.value.substring(end);
        textarea.focus();
        textarea.setSelectionRange(start + linkHtml.length, start + linkHtml.length);
    }
}

function insertImageTag() {
    const url = prompt('Entrez l\'URL de l\'image (ou laissez vide pour utiliser /storage/...):');
    if (url !== null) {
        const alt = prompt('Texte alternatif (alt):', '');
        const textarea = document.getElementById('contenu');
        const cursorPos = textarea.selectionStart;
        
        const imageHtml = '<img src="' + (url || '/storage/...') + '" alt="' + (alt || '') + '" class="img-fluid">';
        textarea.value = textarea.value.substring(0, cursorPos) + imageHtml + textarea.value.substring(cursorPos);
        textarea.focus();
        textarea.setSelectionRange(cursorPos + imageHtml.length, cursorPos + imageHtml.length);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const selectedType = document.querySelector('input[name="type_contenu"]:checked').value;
    if (selectedType === 'html') {
        document.getElementById('editor-toolbar').style.display = 'block';
        document.getElementById('type_html_hint').style.display = 'inline';
    } else {
        document.getElementById('editor-toolbar').style.display = 'block';
        document.getElementById('type_texte_hint').style.display = 'inline';
    }
});
</script>
@endsection

@push('modals')
<div class="modal fade" id="mediaManagerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Médiathèque</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <input type="file" id="mediaUploadInput" accept="image/*" style="display:none;">
                        <button type="button" class="btn btn-sm btn-primary" onclick="document.getElementById('mediaUploadInput').click()">Ajouter une image</button>
                        <small class="text-muted ms-2">PNG/JPG/GIF/WEBP, 5MB max</small>
                    </div>
                    <div id="mediaUploadStatus" class="small text-muted"></div>
                </div>
                <div id="mediaGrid" class="row g-3"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script src="https://cdn.tiny.cloud/1/{{ config('app.tinymce_api_key') }}/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
(function(){
    function initTiny(){
        const toolbarEl = document.getElementById('editor-toolbar');
        if (toolbarEl) toolbarEl.style.display = 'none';
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
                xhr.onload = function() {
                    if (xhr.status !== 200) {
                        failure('HTTP Error: ' + xhr.status);
                        return;
                    }
                    let json;
                    try { json = JSON.parse(xhr.responseText); } catch (e) { failure('Invalid JSON'); return; }
                    if (!json || !json.success || !json.file || !json.file.url) {
                        failure('Upload failed');
                        return;
                    }
                    success(json.file.url);
                };
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                xhr.send(formData);
            },
            file_picker_types: 'image',
            file_picker_callback: function(callback) {
                fetch("{{ route('admin.media.images') }}")
                    .then(r=>r.json())
                    .then(data => {
                        const files = (data && data.files) ? data.files : [];
                        const html = files.length ? files.map(f => '<img src="'+f.url+'" data-url="'+f.url+'" style="width:100px;height:100px;object-fit:cover;margin:6px;cursor:pointer;border-radius:6px;border:1px solid #eee;" />').join('') : '<div style="padding:1rem;">Aucune image</div>';
                        const win = tinymce.activeEditor.windowManager.open({
                            title: 'Sélectionner une image',
                            body: { type: 'panel', items: [{ type: 'htmlpanel', html: '<div style="display:flex;flex-wrap:wrap;max-height:300px;overflow:auto;">'+html+'</div>' }] },
                            buttons: [{ type: 'cancel', text: 'Fermer' }]
                        });
                        setTimeout(function(){
                            const imgs = win.getEl().querySelectorAll('img[data-url]');
                            imgs.forEach(function(img){
                                img.addEventListener('click', function(){ callback(this.getAttribute('data-url')); win.close(); });
                            });
                        }, 0);
                    })
                    .catch(()=>{
                        const input = document.createElement('input');
                        input.type = 'file';
                        input.accept = 'image/*';
                        input.onchange = function(){
                            const file = this.files[0];
                            const reader = new FileReader();
                            reader.onload = function(){ callback(reader.result); };
                            reader.readAsDataURL(file);
                        };
                        input.click();
                    });
            }
        });
    }
    if (window.tinymce) initTiny(); else document.addEventListener('DOMContentLoaded', initTiny);
})();
</script>
@endpush

