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
                xhr.onload = function() {
                    if (xhr.status !== 200) { failure('HTTP Error: ' + xhr.status); return; }
                    let json; try { json = JSON.parse(xhr.responseText); } catch (e) { failure('Invalid JSON'); return; }
                    if (!json || !json.success || !json.file || !json.file.url) { failure('Upload failed'); return; }
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

