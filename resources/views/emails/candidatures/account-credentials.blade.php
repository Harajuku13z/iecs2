@component('emails.layouts.base')
<h2 style="margin:0 0 10px;">Bienvenue {{ $name }} !</h2>
<p style="margin:0 0 14px; line-height:1.6;">Votre compte IESCA a été créé par l'administration. Voici vos identifiants:</p>
<div style="background:#f6f8fb; border:1px solid #e6e9ef; border-radius:8px; padding:12px 16px; margin-bottom:16px;">
  <div><strong>Email:</strong> {{ $email }}</div>
  <div><strong>Mot de passe temporaire:</strong> {{ $password }}</div>
  <div style="font-size:12px; color:#666; margin-top:6px;">Vous pourrez le changer après connexion.</div>
  </div>
<p style="margin:0 0 12px;">Connectez-vous dès maintenant pour compléter votre dossier de candidature:</p>
<div style="text-align:center; margin:20px 0;">
  <a href="{{ url('/login') }}" style="background: linear-gradient(135deg, var(--color-primary, #A66060), var(--color-secondary, #9E5A59)); color:#fff; text-decoration:none; padding:10px 18px; border-radius:6px; display:inline-block;">Se connecter</a>
  </div>
@endcomponent


