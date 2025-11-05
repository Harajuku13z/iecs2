@component('emails.layouts.base')
<h2 style="margin:0 0 10px; color:#222; font-size:20px;">Rappel: pièces manquantes</h2>
<p style="margin:0 0 12px; line-height:1.6; color:#333;">Bonjour {{ $candidature->user->name }},</p>

<div style="background:#f6f8fb; border:1px solid #e6e9ef; border-radius:8px; padding:16px; margin:16px 0;">
  <p style="margin:0 0 12px; line-height:1.6; color:#333;">Nous avons bien reçu votre candidature. Il manque toutefois les pièces suivantes :</p>

  @if(count($missing))
  <ul style="margin:0 0 12px; padding-left:18px; line-height:1.8; color:#333;">
    @foreach($missing as $label)
      <li>{{ $label }}</li>
    @endforeach
  </ul>
  @else
  <p style="margin:0; line-height:1.6; color:#333;">Toutes les pièces requises ont été fournies.</p>
  @endif

  <p style="margin:0; line-height:1.6; color:#333;">Merci de compléter votre dossier dès que possible afin de poursuivre le processus.</p>
</div>

<div style="text-align:center; margin:24px 0;">
  <a href="{{ url('/candidature/edit') }}" style="background: linear-gradient(135deg, {{ \App\Models\Setting::get('color_primary', '#A66060') }}, {{ \App\Models\Setting::get('color_secondary', '#9E5A59') }}); color:#fff; text-decoration:none; padding:12px 24px; border-radius:8px; display:inline-block; font-weight:600;">Compléter mon dossier</a>
</div>

<p style="margin:20px 0 0; color:#333;">Cordialement,<br><strong>L'équipe IESCA</strong></p>
@endcomponent
