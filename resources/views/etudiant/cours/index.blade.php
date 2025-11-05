@extends('layouts.student')

@section('student_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>📚 Mes Cours</h3>
</div>

@if($message ?? false)
    <div class="alert alert-info">
        {{ $message }}
    </div>
@elseif($cours->count() > 0)
    @php
        $grouped = $cours->groupBy(function($c){ return $c->pivot->semestre ?: 'Semestre'; });
    @endphp
    @foreach($grouped as $semestre => $liste)
    <h5 class="mt-3 mb-2">{{ $semestre }}</h5>
    <div class="row g-4">
        @foreach($liste as $c)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $c->nom }}</h5>
                        <p class="text-muted small mb-2">Code: {{ $c->code }}</p>
                        @if($c->description)
                            <p class="card-text small">{{ Str::limit($c->description, 100) }}</p>
                        @endif
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="badge" style="background: var(--color-primary); color: white;">
                                Coef {{ $c->coefficient }}
                            </span>
                            <a href="{{ route('etudiant.cours.show', $c->id) }}" class="btn btn-sm btn-outline-primary">
                                Voir détails
                            </a>
                        </div>
                        @if($c->enseignants->count() > 0)
                            <div class="mt-2">
                                <small class="text-muted">
                                    👨‍🏫 Enseignant(s): 
                                    {{ $c->enseignants->pluck('name')->join(', ') }}
                                </small>
                            </div>
                        @endif
                        @php
                            $mesNotes = $c->notes->where('user_id', auth()->id());
                        @endphp
                        @if($mesNotes->count() > 0)
                            <div class="mt-2">
                                <small class="text-success">
                                    📊 {{ $mesNotes->count() }} note(s) enregistrée(s)
                                </small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @endforeach
@else
    <div class="alert alert-info">
        Aucun cours assigné pour le moment.
    </div>
@endif
@endsection

