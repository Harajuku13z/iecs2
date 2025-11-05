@extends('layouts.app')

@section('title', 'Soumettre ma candidature')

@section('content')
<style>
.candidature-hero {
    background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-black) 100%);
    color: white;
    padding: 4rem 0;
    text-align: center;
    border-radius: 0;
}

.candidature-hero h1 {
    font-size: 3.5rem;
    font-weight: 900;
    margin-bottom: 1rem;
}

.info-card {
    background: white;
    border-radius: 8px;
    padding: 2rem;
    box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    margin-bottom: 2rem;
    border-left: 5px solid var(--color-primary);
}

.sticky-sidebar {
    position: sticky;
    top: 20px;
    align-self: start;
    z-index: 10;
}

.form-select-lg {
    min-height: 48px;
    border-radius: 8px !important;
}

.form-select, .form-control, .form-select-lg {
    border-radius: 8px !important;
}

.form-select option {
    padding: 10px;
    font-size: 1rem;
}

.form-label {
    margin-bottom: 0.5rem;
    font-size: 1rem;
    color: var(--color-black);
}

.alert {
    border-radius: 8px !important;
}

.btn {
    border-radius: 8px !important;
}
</style>

<div class="candidature-hero">
    <div class="container">
        <h1>Soumettre ma Candidature</h1>
        <p class="lead">Complétez vos informations et déposez vos pièces</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-12">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="alert alert-info mb-4" style="border-radius: 8px;">
                <strong>💡 Avant de remplir ce formulaire,</strong> 
                <a href="{{ route('admission') }}" class="alert-link" style="text-decoration: underline;">consultez les conditions d'inscription, les services disponibles et les documents requis</a>.
            </div>

            <div class="info-card" data-aos="fade-up">
                <div class="card-body p-0">
            <form action="{{ route('candidature.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <h5 class="mb-3" style="font-weight:700;">Informations</h5>
                @php
                    $cand = isset($candidature) ? $candidature : null;
                    $prefFiliere = old('filiere_id', request('filiere_id', optional($cand)->filiere_id));
                    $prefClasse = old('classe_id', request('classe_id', optional($cand)->classe_id));
                    $prefSpecialite = old('specialite_id', request('specialite_id', optional($cand)->specialite_id));
                    $docsRaw = optional($cand)->documents;
                    $docsArr = is_array($docsRaw) ? $docsRaw : ($docsRaw ? json_decode($docsRaw, true) : []);
                    $motivationFromDocs = optional(collect($docsArr)->firstWhere('key','doc_lettre'))['text'] ?? '';
                @endphp
                
                @if($prefFiliere || $prefClasse || $prefSpecialite)
                    <div class="alert alert-info mb-3">
                        @if($prefFiliere) 
                            <strong>Filière sélectionnée:</strong> {{ optional(\App\Models\Filiere::find($prefFiliere))->nom }}<br>
                        @endif
                        @if($prefSpecialite) 
                            <strong>Spécialité:</strong> {{ optional(\App\Models\Specialite::find($prefSpecialite))->nom }}<br>
                        @endif
                        @if($prefClasse) 
                            <strong>Classe:</strong> {{ optional(\App\Models\Classe::find($prefClasse))->nom }}
                        @endif
                    </div>
                @endif

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Filière <span class="text-danger">*</span></label>
                        <select id="selFiliere" name="filiere_id" class="form-select form-select-lg" @if($prefClasse) disabled @endif style="font-size: 1rem; padding: 0.75rem;">
                            <option value="">-- Sélectionnez une filière --</option>
                            @foreach(\App\Models\Filiere::orderBy('nom')->get() as $f)
                                <option value="{{ $f->id }}" @selected((string)$prefFiliere === (string)$f->id)>{{ $f->nom }}</option>
                            @endforeach
                        </select>
                        @if($prefFiliere)
                            <input type="hidden" name="filiere_id" value="{{ $prefFiliere }}">
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Niveau d'étude <span class="text-danger">*</span></label>
                        <select id="selNiveau" name="niveau_id" class="form-select form-select-lg" @if($prefClasse) disabled @endif style="font-size: 1rem; padding: 0.75rem;">
                            <option value="">-- Sélectionnez un niveau --</option>
                            @foreach(\App\Models\Niveau::orderBy('ordre')->get() as $n)
                                <option value="{{ $n->id }}" @selected($prefClasse && (string)optional(\App\Models\Classe::find($prefClasse))->niveau_id === (string)$n->id)>{{ $n->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Spécialité <span class="text-danger">*</span></label>
                        <select id="selSpecialite" name="specialite_id" class="form-select form-select-lg" @if($prefClasse) disabled @endif style="font-size: 1rem; padding: 0.75rem;">
                            <option value="">-- Sélectionnez une spécialité --</option>
                            @foreach(\App\Models\Specialite::with('filiere')->orderBy('nom')->get() as $sp)
                                <option value="{{ $sp->id }}" data-filiere="{{ $sp->filiere_id }}" @selected((string)$prefSpecialite === (string)$sp->id)>{{ $sp->nom }}</option>
                            @endforeach
                        </select>
                        @if($prefSpecialite)
                            <input type="hidden" name="specialite_id" value="{{ $prefSpecialite }}">
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Classe <small class="text-muted fw-normal">(optionnel)</small></label>
                        <select id="selClasse" name="classe_id" class="form-select form-select-lg" style="font-size: 1rem; padding: 0.75rem;">
                            <option value="">-- Sélectionnez une classe (optionnel) --</option>
                            @foreach(\App\Models\Classe::with(['filiere','niveau'])->orderBy('nom')->get() as $cl)
                                <option value="{{ $cl->id }}" 
                                        data-filiere="{{ $cl->filiere_id }}" 
                                        data-niveau="{{ $cl->niveau_id }}"
                                        @selected((string)$prefClasse === (string)$cl->id)>
                                    {{ $cl->nom }} - {{ optional($cl->filiere)->nom }} ({{ optional($cl->niveau)->nom }})
                                </option>
                            @endforeach
                        </select>
                        <div id="classeFeedback" class="mt-2" style="display:none;"><small class="text-muted">Aucune classe trouvée pour cette sélection. Essayez de choisir un autre niveau.</small></div>
                        <small class="text-muted d-block mt-1">Si vous ne sélectionnez pas de classe, elle sera déterminée automatiquement selon votre filière, niveau et spécialité.</small>
                        @if($prefClasse)
                            <input type="hidden" name="classe_id" value="{{ $prefClasse }}">
                            <input type="hidden" name="filiere_id" value="{{ optional(\App\Models\Classe::find($prefClasse))->filiere_id }}">
                            <input type="hidden" name="specialite_id" value="{{ $prefSpecialite }}">
                        @endif
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', auth()->user()->phone) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Contact (Nom)</label>
                        <input type="text" name="contact_name" class="form-control" value="{{ old('contact_name', auth()->user()->contact_name) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Contact (Téléphone)</label>
                        <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone', auth()->user()->contact_phone) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Photo de profil</label>
                        <input type="file" id="profilePhotoInput" name="profile_photo" class="form-control" accept=".jpg,.jpeg,.png" />
                        @php $existingPP = auth()->user()->profile_photo; @endphp
                        <div class="mt-2" id="profilePhotoPreviewWrapper" style="{{ $existingPP ? '' : 'display:none;' }}">
                            <img id="profilePhotoPreview" src="{{ $existingPP ? asset('storage/'.$existingPP) : '#' }}" alt="Prévisualisation" style="max-width:100%; border-radius:8px;">
                        </div>
                    </div>
                </div>

                <h5 class="mb-2" style="font-weight:700;">Pièces jointes</h5>
                <div class="row g-3">
                    @php $fields = [
                        'doc_identite' => "Pièce d'identité",
                        'doc_diplome' => 'Diplôme',
                        'doc_releve' => 'Relevé de notes',
                        'doc_lettre' => 'Lettre de motivation (si vous ne saisissez pas de texte)',
                        'doc_cv' => 'CV (optionnel)',
                    ]; 
                    $existingByKey = collect($docsArr)->keyBy('key');
                    @endphp
                    @foreach($fields as $name => $label)
                        <div class="col-md-6">
                            <label class="form-label">{{ $label }}</label>
                            <input type="file" name="{{ $name }}" class="form-control" />
                            @php $doc = $existingByKey->get($name); @endphp
                            @if($doc)
                                <div class="mt-2 d-flex align-items-center gap-2">
                                    <a href="{{ asset('storage/' . $doc['path']) }}" target="_blank" class="btn btn-sm btn-outline-primary">Voir le fichier existant</a>
                                    <span class="small text-muted">{{ $doc['name'] ?? basename($doc['path']) }}</span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="mt-3">
                    <label class="form-label">Lettre de motivation (texte)</label>
                    <textarea name="motivation_text" class="form-control" rows="5" placeholder="Écrivez votre lettre ici si vous ne l'uploadez pas...">{{ old('motivation_text', $motivationFromDocs) }}</textarea>
                    <small class="text-muted">Si vous remplissez ce champ, un PDF sera généré automatiquement.</small>
                </div>
                <div class="mt-4">
                    <button class="btn btn-lg w-100" style="background: var(--color-primary); color: white; font-weight: 700; border-radius: 8px;">Soumettre ma candidature</button>
                </div>
                </form>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- AOS Library -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 1000,
    once: true
  });
</script>

<!-- Cropper.js (CDN) -->
<link href="https://unpkg.com/cropperjs@1.6.2/dist/cropper.min.css" rel="stylesheet">
<script src="https://unpkg.com/cropperjs@1.6.2/dist/cropper.min.js"></script>
<script>
let cropper;
const input = document.getElementById('profilePhotoInput');
const previewWrapper = document.getElementById('profilePhotoPreviewWrapper');
const previewImg = document.getElementById('profilePhotoPreview');
input.addEventListener('change', function(e){
  const file = e.target.files && e.target.files[0];
  if(!file) return;
  const url = URL.createObjectURL(file);
  previewImg.src = url;
  previewWrapper.style.display = '';
  if(cropper) cropper.destroy();
  cropper = new Cropper(previewImg, { aspectRatio: 1, viewMode: 1, background: false });
});

// Intercepter la soumission pour remplacer l'image par le crop carré
document.querySelector('form[action="{{ route('candidature.store') }}"]').addEventListener('submit', async function(e){
  if(cropper){
    const canvas = cropper.getCroppedCanvas({ width: 600, height: 600, imageSmoothingQuality: 'high' });
    const dataUrl = canvas.toDataURL('image/png');
    const blob = await (await fetch(dataUrl)).blob();
    const file = new File([blob], 'profile_cropped.png', { type: 'image/png' });
    const dt = new DataTransfer();
    dt.items.add(file);
    input.files = dt.files;
  }
});

// Filtering logic for selects
const selFiliere = document.getElementById('selFiliere');
const selNiveau = document.getElementById('selNiveau');
const selSpecialite = document.getElementById('selSpecialite');
const selClasse = document.getElementById('selClasse');

function filterSpecialites() {
  if (!selSpecialite) return;
  let filiere = selFiliere ? selFiliere.value : '';
  // Si filière est disabled, utiliser la valeur depuis l'input hidden
  if (!filiere && selFiliere && selFiliere.disabled) {
    const hiddenInput = document.querySelector('input[name="filiere_id"][type="hidden"]');
    if (hiddenInput) {
      filiere = hiddenInput.value;
    }
  }
  [...selSpecialite.options].forEach((opt, idx) => {
    if (idx === 0) return; // placeholder
    opt.hidden = filiere && opt.getAttribute('data-filiere') !== filiere;
  });
  if (!selSpecialite.disabled) {
    selSpecialite.value = '';
  }
}

function filterClasses() {
  if (!selClasse) return;
  let filiere = selFiliere ? selFiliere.value : '';
  const niveau = selNiveau ? selNiveau.value : '';
  const niveauText = selNiveau && selNiveau.selectedIndex >= 0 ? (selNiveau.options[selNiveau.selectedIndex].textContent || '') : '';
  
  // Si une spécialité est sélectionnée, utiliser sa filière associée (priorité à la spécialité)
  if (selSpecialite && selSpecialite.value) {
    const spOpt = selSpecialite.options[selSpecialite.selectedIndex];
    const spFiliere = spOpt ? spOpt.getAttribute('data-filiere') : '';
    if (spFiliere) {
      filiere = spFiliere;
      // Si la filière n'est pas encore sélectionnée dans le champ filière, la pré-sélectionner visuellement
      if (selFiliere && !selFiliere.value && !selFiliere.disabled) {
        selFiliere.value = spFiliere;
      }
    }
  }
  
  // Si filière est disabled, utiliser la valeur depuis l'input hidden
  if (!filiere && selFiliere && selFiliere.disabled) {
    const hiddenInput = document.querySelector('input[name="filiere_id"][type="hidden"]');
    if (hiddenInput) {
      filiere = hiddenInput.value;
    }
  }
  
  // Déterminer les niveaux Licence autorisés en fonction du choix
  function findNiveauIdByLabel(regex) {
    const opt = [...(selNiveau ? selNiveau.options : [])].find(o => regex.test((o.textContent || '')));
    return opt ? opt.value : null;
  }
  const idL1 = findNiveauIdByLabel(/\bL1\b|Licence\s*1/i);
  const idL2 = findNiveauIdByLabel(/\bL2\b|Licence\s*2/i);
  const idL3 = findNiveauIdByLabel(/\bL3\b|Licence\s*3/i);
  const isBac = /\bBac\b/i.test(niveauText) && !/\bL\d\b/i.test(niveauText);
  const isPrepBac = /pr[eé]pare\s*mon\s*bac/i.test(niveauText);

  let allowedNiveauIds = [];
  if (isBac || isPrepBac) {
    if (idL1) allowedNiveauIds = [idL1];
  } else if (/\bL1\b|Licence\s*1/i.test(niveauText)) {
    allowedNiveauIds = [idL1, idL2].filter(Boolean);
  } else if (/\bL2\b|Licence\s*2/i.test(niveauText)) {
    allowedNiveauIds = [idL2, idL3].filter(Boolean);
  } else if (/\bL3\b|Licence\s*3/i.test(niveauText)) {
    allowedNiveauIds = [idL1, idL2, idL3].filter(Boolean);
  } else if (niveau) {
    // Cas générique: respecter le niveau choisi tel quel
    allowedNiveauIds = [niveau];
  }

  let visibleCount = 0;
  [...selClasse.options].forEach((opt, idx) => {
    if (idx === 0) {
      opt.hidden = false; // Toujours montrer l'option placeholder
      return;
    }
    const optFiliere = opt.getAttribute('data-filiere');
    const optNiveau = opt.getAttribute('data-niveau');
    
    const okF = !filiere || optFiliere === filiere;
    // Si allowedNiveauIds est défini, se baser dessus, sinon utiliser la valeur de niveau telle quelle
    const okN = allowedNiveauIds.length
      ? allowedNiveauIds.includes(optNiveau)
      : (!niveau || optNiveau === niveau);
    
    opt.hidden = !(okF && okN);
    if (!opt.hidden && opt.value) visibleCount++;
  });
  
  // Feedback utilisateur
  const feedback = document.getElementById('classeFeedback');
  if (feedback) {
    if (visibleCount === 0) {
      feedback.style.display = '';
    } else {
      feedback.style.display = 'none';
    }
  }

  // Si une seule classe correspond et aucune n'est sélectionnée, la sélectionner automatiquement
  if (visibleCount === 1 && !selClasse.value) {
    const visibleClass = [...selClasse.options].find(opt => !opt.hidden && opt.value);
    if (visibleClass) {
      selClasse.value = visibleClass.value;
    }
  }
  
  // Si aucune classe visible et une était sélectionnée, réinitialiser
  if (visibleCount === 0 && selClasse.value) {
    selClasse.value = '';
  }
}

// Initialiser les filtres au chargement
if (selFiliere) {
  selFiliere.addEventListener('change', () => { 
    filterSpecialites(); 
    filterClasses(); 
  });
  // Déclencher le filtre si une filière est pré-sélectionnée
  if (selFiliere.value) {
    filterSpecialites();
    filterClasses();
  }
}

if (selNiveau) {
  selNiveau.addEventListener('change', filterClasses);
  // Déclencher le filtre si un niveau est pré-sélectionné
  if (selNiveau.value) {
    filterClasses();
  }
}

if (selSpecialite) {
  selSpecialite.addEventListener('change', () => {
    // Activer visuellement la filière liée si vide
    const spOpt = selSpecialite.options[selSpecialite.selectedIndex];
    const spFiliere = spOpt ? spOpt.getAttribute('data-filiere') : '';
    if (selFiliere && spFiliere && !selFiliere.value && !selFiliere.disabled) {
      selFiliere.value = spFiliere;
    }
    // Si aucun niveau sélectionné, essayer de sélectionner automatiquement L1 (Licence 1)
    if (selNiveau && !selNiveau.value && !selNiveau.disabled) {
      const l1Option = [...selNiveau.options].find(opt => /\bL1\b|Licence\s*1/i.test(opt.textContent || ''));
      if (l1Option) {
        selNiveau.value = l1Option.value;
      }
    }
    filterClasses();
  });
  // Déclencher le filtre si une spécialité est pré-sélectionnée
  if (selSpecialite.value) {
    // idem au chargement : pré-remplir la filière et L1 par défaut si possible
    const spOpt = selSpecialite.options[selSpecialite.selectedIndex];
    const spFiliere = spOpt ? spOpt.getAttribute('data-filiere') : '';
    if (selFiliere && spFiliere && !selFiliere.value && !selFiliere.disabled) {
      selFiliere.value = spFiliere;
    }
    if (selNiveau && !selNiveau.value && !selNiveau.disabled) {
      const l1Option = [...selNiveau.options].find(opt => /\bL1\b|Licence\s*1/i.test(opt.textContent || ''));
      if (l1Option) {
        selNiveau.value = l1Option.value;
      }
    }
    filterClasses();
  }
}

// Initialiser les filtres au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
  filterSpecialites();
  filterClasses();
});
</script>
@endsection


