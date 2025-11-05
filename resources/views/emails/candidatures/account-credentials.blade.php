@component('emails.layouts.base')
<h2 style="margin:0 0 10px; color:#222; font-size:20px;">Bienvenue {{ $name }} !</h2>
<p style="margin:0 0 14px; line-height:1.6; color:#333;">Votre compte IESCA a été créé par l'administration. Voici vos identifiants :</p>

<div style="background:#f6f8fb; border:1px solid #e6e9ef; border-radius:8px; padding:16px; margin:16px 0;">
  <div style="margin-bottom:8px; line-height:1.6; color:#333;"><strong>Email:</strong> {{ $email }}</div>
  <div style="margin-bottom:8px; line-height:1.6; color:#333;"><strong>Mot de passe temporaire:</strong> <code style="background:#fff; padding:2px 6px; border-radius:4px; font-family:monospace;">{{ $password }}</code></div>
  <div style="font-size:12px; color:#666; margin-top:8px;">Vous pourrez le changer après connexion.</div>
</div>

<p style="margin:0 0 12px; line-height:1.6; color:#333;">Connectez-vous dès maintenant pour compléter votre dossier de candidature :</p>

<div style="text-align:center; margin:24px 0;">
  <a href="{{ url('/login') }}" style="background: linear-gradient(135deg, {{ \App\Models\Setting::get('color_primary', '#A66060') }}, {{ \App\Models\Setting::get('color_secondary', '#9E5A59') }}); color:#fff; text-decoration:none; padding:12px 24px; border-radius:8px; display:inline-block; font-weight:600;">Se connecter</a>
</div>

<p style="margin:20px 0 0; color:#333;">Cordialement,<br><strong>L'équipe IESCA</strong></p>
@endcomponent
