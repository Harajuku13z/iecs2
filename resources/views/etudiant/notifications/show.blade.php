@extends('layouts.student')

@section('student_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>{{ $notification->titre }}</h3>
        <p class="text-muted mb-0">
            {{ $notification->created_at->format('d/m/Y à H:i') }}
            @if($notification->sender)
                • De: {{ $notification->sender->name }}
            @endif
        </p>
    </div>
    <a href="{{ route('etudiant.notifications.index') }}" class="btn btn-sm btn-outline-secondary">← Retour</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="d-flex align-items-center gap-2 mb-3">
            <span class="badge bg-{{ $notification->type === 'info' ? 'info' : ($notification->type === 'warning' ? 'warning' : ($notification->type === 'success' ? 'success' : ($notification->type === 'danger' ? 'danger' : 'secondary'))) }}">
                {{ ucfirst($notification->type) }}
            </span>
            @if($notification->type === 'message')
                <span class="badge bg-primary">Message personnel</span>
            @endif
        </div>
        
        <div class="mb-4">
            <p style="white-space: pre-wrap;">{{ $notification->contenu }}</p>
        </div>
        
        @if($notification->classe)
            <div class="mb-2">
                <small class="text-muted">Classe concernée: {{ $notification->classe->nom }}</small>
            </div>
        @endif
        
        @if($notification->envoye_email)
            <div class="alert alert-info">
                <small>📧 Cette notification vous a également été envoyée par email.</small>
            </div>
        @endif
    </div>
</div>
@endsection

