@extends('layouts.admin')

@section('title', 'Créer une Page Statique')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>📄 Créer une Page Statique</h1>
    <a href="{{ route('admin.static-pages.index') }}" class="btn btn-secondary">
        ← Retour
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.static-pages.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

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
                               id="titre" name="titre" value="{{ old('titre') }}" required>
                        @error('titre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description courte -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description courte (optionnel)</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="2">{{ old('description') }}</textarea>
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
                                   {{ old('type_contenu', 'texte') === 'texte' ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary" for="type_texte">📝 Texte simple</label>
                            
                            <input type="radio" class="btn-check" name="type_contenu" id="type_html" value="html"
                                   {{ old('type_contenu') === 'html' ? 'checked' : '' }}>
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
                                  id="contenu" name="contenu" rows="15" required>{{ old('contenu') }}</textarea>
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
                        <input type="file" class="form-control @error('image_principale') is-invalid @enderror" 
                               id="image_principale" name="image_principale" accept="image/*">
                        <small class="text-muted">Format: JPG, PNG, GIF, WEBP (max 5MB)</small>
                        <div id="image-preview" class="mt-2" style="display: none;">
                            <img id="preview-img" src="" alt="Aperçu" class="img-thumbnail" style="max-width: 100%;">
                        </div>
                        @error('image_principale')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Menu -->
                    <div class="mb-3">
                        <label for="menu_nom" class="form-label">Nom dans le menu</label>
                        <input type="text" class="form-control" id="menu_nom" name="menu_nom" 
                               value="{{ old('menu_nom') }}" placeholder="Laissez vide pour utiliser le titre">
                        <small class="text-muted">Nom affiché dans le menu de navigation</small>
                    </div>

                    <div class="mb-3">
                        <label for="menu_parent" class="form-label">Menu parent (sous-menu)</label>
                        <select class="form-select" id="menu_parent" name="menu_parent">
                            <option value="">Aucun (menu principal)</option>
                            @foreach($mainMenuItems as $item)
                                <option value="{{ $item->menu_nom }}" {{ old('menu_parent') === $item->menu_nom ? 'selected' : '' }}>
                                    {{ $item->menu_nom }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Sélectionnez un menu parent pour créer un sous-menu</small>
                    </div>

                    <div class="mb-3">
                        <label for="menu_ordre" class="form-label">Ordre dans le menu</label>
                        <input type="number" class="form-control" id="menu_ordre" name="menu_ordre" 
                               value="{{ old('menu_ordre', 0) }}" min="0">
                        <small class="text-muted">Ordre d'affichage (0 = premier)</small>
                    </div>

                    <!-- Options -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="hidden" name="afficher_menu" value="0">
                            <input class="form-check-input" type="checkbox" id="afficher_menu" name="afficher_menu" value="1"
                                   {{ old('afficher_menu', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="afficher_menu">
                                Afficher dans le menu
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="hidden" name="publie" value="0">
                            <input class="form-check-input" type="checkbox" id="publie" name="publie" value="1"
                                   {{ old('publie', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="publie">
                                Publier immédiatement
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">💾 Créer la page</button>
                <a href="{{ route('admin.static-pages.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<script>
// Afficher/masquer la barre d'outils selon le type
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

// Afficher l'aperçu de l'image
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

// Fonctions de formatage
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

// Initialiser l'affichage
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
<script>
let mediaModalInstance;
function openMediaManager(){
    const modalEl = document.getElementById('mediaManagerModal');
    if (!modalEl) {
        console.error('Modal element not found');
        return;
    }
    if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap is not loaded');
        return;
    }
    mediaModalInstance = new bootstrap.Modal(modalEl);
    mediaModalInstance.show();
    loadMediaImages();
}

// Attacher l'event listener au bouton
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('btn-open-media');
    if (btn) {
        btn.addEventListener('click', openMediaManager);
    }
});

function loadMediaImages(){
    const grid = document.getElementById('mediaGrid');
    grid.innerHTML = '<div class="text-center py-4">Chargement...</div>';
    fetch("{{ route('admin.media.images') }}", { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
        .then(r => r.json())
        .then(data => {
            grid.innerHTML = '';
            if(!data.files || data.files.length === 0){
                grid.innerHTML = '<div class="text-center py-4 text-muted">Aucune image trouvée dans storage.</div>';
                return;
            }
            data.files.forEach(file => {
                const col = document.createElement('div');
                col.className = 'col-6 col-md-4 col-lg-3';
                col.innerHTML = `
                    <div class=\"card h-100 shadow-sm\" style=\"cursor:pointer;\">
                        <img src=\"${file.url}\" class=\"card-img-top\" style=\"height:120px; object-fit:cover;\">
                        <div class=\"card-body p-2\">
                            <div class=\"small text-truncate\" title=\"${file.name}\">${file.name}</div>
                            <button type=\"button\" class=\"btn btn-sm btn-outline-primary w-100 mt-2\">Insérer</button>
                        </div>
                    </div>`;
                col.querySelector('button').addEventListener('click', () => insertImageFromManager(file.url));
                grid.appendChild(col);
            });
        })
        .catch(() => {
            grid.innerHTML = '<div class="text-center py-4 text-danger">Erreur lors du chargement des images.</div>';
        });
}

function insertImageFromManager(url){
    const textarea = document.getElementById('contenu');
    const cursorPos = textarea.selectionStart || textarea.value.length;
    const html = '<img src=\"' + url + '\" alt=\"\" class=\"img-fluid\">';
    textarea.value = textarea.value.substring(0, cursorPos) + html + textarea.value.substring(cursorPos);
    textarea.focus();
    textarea.setSelectionRange(cursorPos + html.length, cursorPos + html.length);
    if(mediaModalInstance){ mediaModalInstance.hide(); }
}

// Upload handling
const uploadInput = document.getElementById('mediaUploadInput');
if(uploadInput){
    uploadInput.addEventListener('change', function(){
        const file = this.files && this.files[0];
        if(!file) return;
        const status = document.getElementById('mediaUploadStatus');
        status.textContent = 'Téléversement...';
        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', '{{ csrf_token() }}');
        fetch("{{ route('admin.media.upload') }}", { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => {
                status.textContent = data.success ? 'Image ajoutée.' : 'Échec du téléversement.';
                loadMediaImages();
                uploadInput.value = '';
            })
            .catch(() => {
                status.textContent = 'Erreur réseau.';
            });
    });
}
</script>
@endpush
@endsection

