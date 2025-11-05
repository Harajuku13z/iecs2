@component('emails.layouts.base')
<h2 style="margin:0 0 10px; color:#222; font-size:20px;">Mise à jour de votre candidature</h2>
<p style="margin:0 0 12px; line-height:1.6; color:#333;">Bonjour {{ $candidature->user->name }},</p>

<div style="background:#f6f8fb; border:1px solid #e6e9ef; border-radius:8px; padding:16px; margin:16px 0;">
  <p style="margin:0 0 12px; line-height:1.6; color:#333;">Le statut de votre candidature a été mis à jour : <strong style="color:#222;">{{ ucfirst($candidature->statut) }}</strong>.</p>

  @if($candidature->statut === 'soumis')
  <p style="margin:0; line-height:1.6; color:#333;">Votre dossier a été soumis avec succès. Il sera vérifié sous peu.</p>
  @elseif($candidature->statut === 'verifie')
  <p style="margin:0; line-height:1.6; color:#333;">Votre dossier a été vérifié. Il passe à l'étape suivante.</p>
  @elseif($candidature->statut === 'admis')
  <p style="margin:0 0 12px; line-height:1.6; color:#333;">Félicitations ! Vous avez été admis(e).</p>
  <p style="margin:0; line-height:1.6; color:#333;">Pour finaliser votre inscription, merci de vous acquitter des <strong>frais d'inscription</strong> d'un montant de <strong>{{ number_format(\App\Models\Setting::get('frais_inscription', 30000), 0, ',', ' ') }} FCFA</strong> auprès du secrétariat.</p>
  @elseif($candidature->statut === 'rejete')
  <p style="margin:0; line-height:1.6; color:#333;">Nous sommes désolés, votre candidature n'a pas été retenue.</p>
  @endif

  @if($candidature->evaluation_date)
  <div style="margin-top:12px; padding-top:12px; border-top:1px solid #ddd;">
    <p style="margin:0; line-height:1.6; color:#333;">Date d'évaluation prévue: <strong>{{ \Carbon\Carbon::parse($candidature->evaluation_date)->format('d/m/Y à H:i') }}</strong></p>
  </div>
  @endif

  @if($candidature->commentaire_admin)
  <div style="margin-top:12px; padding-top:12px; border-top:1px solid #ddd;">
    <p style="margin:0; line-height:1.6; color:#333;"><strong>Commentaire :</strong> {{ $candidature->commentaire_admin }}</p>
  </div>
  @endif
</div>

<div style="text-align:center; margin:24px 0;">
  <a href="{{ url('/candidature/edit') }}" style="background: linear-gradient(135deg, {{ \App\Models\Setting::get('color_primary', '#A66060') }}, {{ \App\Models\Setting::get('color_secondary', '#9E5A59') }}); color:#fff; text-decoration:none; padding:12px 24px; border-radius:8px; display:inline-block; font-weight:600;">Voir mon dossier</a>
</div>

<p style="margin:20px 0 0; color:#333;">Cordialement,<br><strong>L'équipe IESCA</strong></p>
@endcomponent

<h2 style="margin:0 0 10px;">Mise à jour de votre candidature</h2>
<p style="margin:0 0 12px;">Bonjour {{ $candidature->user->name }},</p>
<p style="margin:0 0 12px;">Le statut de votre candidature a été mis à jour : <strong>{{ ucfirst($candidature->statut) }}</strong>.</p>

@if($candidature->statut === 'soumis')
<p style="margin:0 0 12px;">Votre dossier a été soumis avec succès. Il sera vérifié sous peu.</p>
@elseif($candidature->statut === 'verifie')
<p style="margin:0 0 12px;">Votre dossier a été vérifié. Il passe à l'étape suivante.</p>
@elseif($candidature->statut === 'admis')
<p style="margin:0 0 12px;">Félicitations ! Vous avez été admis(e).</p>
<p style="margin:0 0 12px;">Pour finaliser votre inscription, merci de vous acquitter des <strong>frais d'inscription</strong> d'un montant de <strong>{{ number_format(\App\Models\Setting::get('frais_inscription', 30000), 0, ',', ' ') }} FCFA</strong> auprès du secrétariat.</p>
@elseif($candidature->statut === 'rejete')
<p style="margin:0 0 12px;">Nous sommes désolés, votre candidature n'a pas été retenue.</p>
@endif

@if($candidature->evaluation_date)
<p style="margin:0;">Date d'évaluation prévue: <strong>{{ \Carbon\Carbon::parse($candidature->evaluation_date)->format('d/m/Y H:i') }}</strong></p>
@endif
@endcomponent

@if($candidature->commentaire_admin)
<p><strong>Commentaire :</strong> {{ $candidature->commentaire_admin }}</p>
@endif

<p>Cordialement,<br>L'équipe IESCA</p>


