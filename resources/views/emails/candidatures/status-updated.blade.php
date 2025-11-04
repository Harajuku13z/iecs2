<p>Bonjour {{ $candidature->user->name }},</p>

<p>Le statut de votre candidature a été mis à jour : <strong>{{ ucfirst($candidature->statut) }}</strong>.</p>

@if($candidature->statut === 'soumis')
<p>Votre dossier a été soumis avec succès. Il sera vérifié sous peu.</p>
@elseif($candidature->statut === 'verifie')
<p>Votre dossier a été vérifié. Il passe à l'étape suivante.</p>
@elseif($candidature->statut === 'admis')
<p>Félicitations ! Vous avez été admis(e). L'équipe prendra contact avec vous pour la suite.</p>
@elseif($candidature->statut === 'rejete')
<p>Nous sommes désolés, votre candidature n'a pas été retenue.</p>
@endif

@if($candidature->commentaire_admin)
<p><strong>Commentaire :</strong> {{ $candidature->commentaire_admin }}</p>
@endif

<p>Cordialement,<br>L'équipe IESCA</p>


