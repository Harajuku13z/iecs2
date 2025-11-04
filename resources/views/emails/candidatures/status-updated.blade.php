@component('emails.layouts.base')
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


