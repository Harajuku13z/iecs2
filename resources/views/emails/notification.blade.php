@component('emails.layouts.base')
<h2 style="margin:0 0 10px; color:#222; font-size:20px;">{{ $notification->titre }}</h2>
<p style="margin:0 0 12px; line-height:1.6; color:#333;">Bonjour{{ $notification->user ? ' ' . $notification->user->name : '' }},</p>

<div style="background:#f6f8fb; border:1px solid #e6e9ef; border-radius:8px; padding:16px; margin:16px 0;">
  <p style="margin:0; white-space:pre-wrap; line-height:1.6; color:#333;">{{ $notification->contenu }}</p>
</div>

@php
  // Détecter si c'est une notification de document non conforme
  $isDocumentNonConforme = str_contains(strtolower($notification->titre), 'document non conforme') || 
                          str_contains(strtolower($notification->contenu), 'non conforme');
  $candidatureId = null;
  if (preg_match('/#(\d+)/', $notification->titre . ' ' . $notification->contenu, $matches)) {
    $candidatureId = $matches[1] ?? null;
  }
  // Si pas trouvé dans le texte, chercher via l'utilisateur
  if (!$candidatureId && $notification->user) {
    $cand = \App\Models\Candidature::where('user_id', $notification->user->id)->first();
    $candidatureId = $cand ? $cand->id : null;
  }
@endphp

@if($isDocumentNonConforme && $candidatureId)
<div style="text-align:center; margin:24px 0;">
  <a href="{{ url('/candidature/edit') }}" style="background: linear-gradient(135deg, {{ \App\Models\Setting::get('color_primary', '#A66060') }}, {{ \App\Models\Setting::get('color_secondary', '#9E5A59') }}); color:#fff; text-decoration:none; padding:12px 24px; border-radius:8px; display:inline-block; font-weight:600;">Modifier mon dossier</a>
</div>
@endif

@if($notification->sender)
<div style="margin-top:16px; padding-top:16px; border-top:1px solid #eee;">
  <p style="margin:0; font-size:13px; color:#666;">
    <strong>Expéditeur:</strong> {{ $notification->sender->name }}
  </p>
  <p style="margin:4px 0 0; font-size:13px; color:#666;">
    <strong>Date:</strong> {{ $notification->created_at->format('d/m/Y à H:i') }}
  </p>
</div>
@else
<div style="margin-top:16px; padding-top:16px; border-top:1px solid #eee;">
  <p style="margin:0; font-size:13px; color:#666;">
    <strong>Date:</strong> {{ $notification->created_at->format('d/m/Y à H:i') }}
  </p>
</div>
@endif

<p style="margin:20px 0 0; color:#333;">Cordialement,<br><strong>L'équipe IESCA</strong></p>
@endcomponent
