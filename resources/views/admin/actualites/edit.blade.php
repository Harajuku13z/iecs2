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
                <textarea class="form-control @error('contenu') is-invalid @enderror" 
                          id="contenu" name="contenu" rows="10" required>{{ old('contenu', $actualite->contenu) }}</textarea>
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
                                html += '<div style="position:relative;cursor:pointer;border:2px solid #ddd;border-radius:8px;overflow:hidden;transition:border-color 0.2s;" onmouseover="this.style.borderColor=\'#007bff\'" onmouseout="this.style.borderColor=\'#ddd\'" onclick="window.selectedImageCallback('+JSON.stringify(f.url)+');">';
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
                        
                        window.selectedImageCallback = function(url) {
                            callback(url, {alt: ''});
                            win.close();
                        };
                    })
                    .catch(err => {
                        console.error('Error loading images:', err);
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

