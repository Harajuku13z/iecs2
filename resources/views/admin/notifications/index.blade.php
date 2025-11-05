@extends('layouts.admin')

@section('title', 'Gestion des Notifications')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>🔔 Gestion des Notifications</h1>
    <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary">➕ Envoyer une notification</a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Destinataire</th>
                        <th>Expéditeur</th>
                        <th>Date</th>
                        <th>Dossier</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notifications as $notification)
                        <tr>
                            <td>{{ $notification->titre }}</td>
                            <td style="max-width:420px;">
                                @php
                                    $content = $notification->contenu;
                                    $desc = null;
                                    $decoded = null;
                                    try { $decoded = is_array($content) ? $content : json_decode($content, true); } catch (\Throwable $e) { $decoded = null; }
                                @endphp
                                @if(is_array($decoded))
                                    @if(isset($decoded['changes']) && is_array($decoded['changes']) && count($decoded['changes']))
                                        <ul class="mb-0 small">
                                            @foreach($decoded['changes'] as $field => $change)
                                                <li><strong>{{ ucfirst(str_replace('_',' ', $field)) }}</strong>: {{ $change['old'] ?? '—' }} → {{ $change['new'] ?? '—' }}</li>
                                            @endforeach
                                        </ul>
                                    @elseif(isset($decoded['message']))
                                        <span class="small text-muted">{{ Str::limit($decoded['message'], 140) }}</span>
                                    @else
                                        <span class="small text-muted">{{ Str::limit($notification->contenu, 140) }}</span>
                                    @endif
                                @else
                                    <span class="small text-muted">{{ Str::limit($notification->contenu, 140) }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $notification->type === 'info' ? 'info' : ($notification->type === 'warning' ? 'warning' : ($notification->type === 'success' ? 'success' : ($notification->type === 'danger' ? 'danger' : 'secondary'))) }}">
                                    {{ ucfirst($notification->type) }}
                                </span>
                            </td>
                            <td>
                                @if($notification->user)
                                    {{ $notification->user->name }}
                                @elseif($notification->classe)
                                    Classe: {{ $notification->classe->nom }}
                                @else
                                    Tous
                                @endif
                            </td>
                            <td>{{ $notification->sender->name ?? 'Système' }}</td>
                            <td>{{ $notification->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @php
                                    $cand = $notification->user ? \App\Models\Candidature::where('user_id', $notification->user->id)->first() : null;
                                    // Essayer d'extraire un ID de candidature depuis le titre / contenu (ex: #12)
                                    $candId = null;
                                    if(preg_match('/#(\d+)/', ($notification->titre . ' ' . $notification->contenu), $m)) { $candId = (int)($m[1] ?? 0); }
                                    if(!$cand && $candId) { $cand = \App\Models\Candidature::find($candId); }
                                @endphp
                                @if($cand)
                                    <a href="{{ route('admin.candidatures.show', $cand) }}" class="btn btn-sm btn-outline-primary">Voir la candidature</a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <form action="{{ route('admin.notifications.destroy', $notification) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Supprimer cette notification ?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Aucune notification</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $notifications->links() }}
        </div>
    </div>
</div>
@endsection



