@extends('layouts.enseignant')

@section('teacher_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>📚 Mes Cours</h3>
</div>

@if($cours->count() > 0)
    <div class="row g-4">
        @foreach($cours as $c)
            <div class="col-md-6 col-lg-4">
                <div class="teacher-card h-100">
                    <div class="teacher-card-header">
                        <h5 class="mb-0">{{ $c->nom }}</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-2">
                            <strong>Code:</strong> {{ $c->code }} | 
                            <strong>Coef:</strong> {{ $c->coefficient }}
                        </p>
                        
                        @if($c->description)
                            <p class="card-text small mb-3">{{ Str::limit($c->description, 100) }}</p>
                        @endif
                        
                        @if($c->classes && $c->classes->count() > 0)
                            <div class="mb-3">
                                <strong class="small text-muted d-block mb-2">Classes:</strong>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($c->classes as $classe)
                                        <span class="badge" style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); color: white; font-size: 0.75rem;">
                                            🏫 {{ $classe->nom }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="mb-3">
                                <small class="text-muted">Aucune classe assignée</small>
                            </div>
                        @endif
                        
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">
                                📊 {{ $c->notes->count() }} notes
                            </small>
                            <a href="{{ route('enseignant.cours.show', $c->id) }}" class="btn btn-sm" style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); color: white; border: none; font-weight: 600;">
                                Voir détails
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="teacher-card">
        <div class="card-body text-center py-5">
            <p class="text-muted mb-0">Aucun cours assigné pour le moment.</p>
        </div>
    </div>
@endif
@endsection

