@extends('layouts.enseignant')

@section('teacher_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>📝 Examens</h3>
    <a href="{{ route('enseignant.examens.create') }}" class="btn btn-primary">➕ Créer un examen</a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body">
        @if($cours->count() > 0)
            @foreach($cours as $c)
                @php
                    $examensCours = \App\Models\Note::where('cours_id', $c->id)
                        ->select('type_evaluation', 'semestre')
                        ->distinct()
                        ->get();
                @endphp
                @if($examensCours->count() > 0)
                    <div class="mb-4">
                        <h5>{{ $c->nom }} ({{ $c->code }})</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Type d'examen</th>
                                        <th>Semestre</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($examensCours as $examen)
                                        <tr>
                                            <td>{{ $examen->type_evaluation }}</td>
                                            <td>{{ $examen->semestre ?? '-' }}</td>
                                            <td>
                                                <a href="{{ route('enseignant.examens.show', ['cours' => $c->id, 'type' => urlencode($examen->type_evaluation)]) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    Voir les notes
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            @endforeach
        @else
            <div class="alert alert-info">
                Aucun examen créé. <a href="{{ route('enseignant.examens.create') }}">Créer un examen</a>
            </div>
        @endif
    </div>
</div>
@endsection

