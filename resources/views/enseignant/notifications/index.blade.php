@extends('layouts.enseignant')

@section('teacher_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>🔔 Notifications</h3>
    <a href="{{ route('enseignant.notifications.create') }}" class="btn btn-primary">➕ Envoyer une notification</a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h5 class="mb-3">Notifications envoyées</h5>
        @if($notificationsEnvoyees->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Type</th>
                            <th>Classe</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($notificationsEnvoyees as $notification)
                            <tr>
                                <td>{{ $notification->titre }}</td>
                                <td>
                                    <span class="badge bg-{{ $notification->type === 'info' ? 'info' : ($notification->type === 'warning' ? 'warning' : ($notification->type === 'success' ? 'success' : 'danger')) }}">
                                        {{ ucfirst($notification->type) }}
                                    </span>
                                </td>
                                <td>{{ $notification->classe->nom ?? '-' }}</td>
                                <td>{{ $notification->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $notificationsEnvoyees->links() }}
            </div>
        @else
            <p class="text-muted">Aucune notification envoyée.</p>
        @endif
    </div>
</div>
@endsection


