@extends('layouts.student')

@section('student_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>🔔 Mes Notifications</h3>
    @if(auth()->user()->notificationsNonLues()->count() > 0)
        <span class="badge bg-danger">{{ auth()->user()->notificationsNonLues()->count() }} non lue(s)</span>
    @endif
</div>

@if($notifications->count() > 0)
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @foreach($notifications as $notification)
                <div class="border-bottom pb-3 mb-3 {{ !$notification->lu ? 'border-start border-3 border-primary ps-3' : '' }}">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <h6 class="mb-0">{{ $notification->titre }}</h6>
                                @if(!$notification->lu)
                                    <span class="badge bg-primary">Nouveau</span>
                                @endif
                                <span class="badge bg-{{ $notification->type === 'info' ? 'info' : ($notification->type === 'warning' ? 'warning' : ($notification->type === 'success' ? 'success' : ($notification->type === 'danger' ? 'danger' : 'secondary'))) }}">
                                    {{ ucfirst($notification->type) }}
                                </span>
                            </div>
                            <p class="text-muted mb-2">{{ Str::limit($notification->contenu, 150) }}</p>
                            <div class="d-flex gap-3">
                                <small class="text-muted">
                                    @if($notification->sender)
                                        De: {{ $notification->sender->name }}
                                    @endif
                                </small>
                                <small class="text-muted">{{ $notification->created_at->format('d/m/Y à H:i') }}</small>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('etudiant.notifications.show', $notification) }}" class="btn btn-sm btn-outline-primary">
                                Voir
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
            
            <div class="mt-3">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
@else
    <div class="alert alert-info">
        Aucune notification pour le moment.
    </div>
@endif
@endsection


