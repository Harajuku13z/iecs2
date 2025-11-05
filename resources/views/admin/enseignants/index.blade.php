@extends('layouts.admin')

@section('title', 'Gestion des Enseignants')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>👨‍🏫 Gestion des Enseignants</h1>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Cours</th>
                        <th>Classes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($enseignants as $enseignant)
                        <tr>
                            <td>{{ $enseignant->name }}</td>
                            <td>{{ $enseignant->email }}</td>
                            <td>
                                @if($enseignant->cours->count() > 0)
                                    <span class="badge bg-primary">{{ $enseignant->cours->count() }} cours</span>
                                @else
                                    <span class="text-muted">Aucun cours</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $classesIds = \DB::table('cours_user')
                                        ->where('user_id', $enseignant->id)
                                        ->distinct()
                                        ->pluck('classe_id')
                                        ->filter();
                                    $nbClasses = $classesIds->count();
                                @endphp
                                @if($nbClasses > 0)
                                    <span class="badge bg-info">{{ $nbClasses }} classe(s)</span>
                                @else
                                    <span class="text-muted">Aucune classe</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.enseignants.show', $enseignant) }}" class="btn btn-sm btn-primary">Voir détails</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Aucun enseignant trouvé</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $enseignants->links() }}
        </div>
    </div>
</div>
@endsection


