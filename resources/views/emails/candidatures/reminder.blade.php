<p>Bonjour {{ $candidature->user->name }},</p>

<p>Nous avons bien reçu votre candidature. Il manque toutefois les pièces suivantes :</p>

@if(count($missing))
<ul>
    @foreach($missing as $label)
        <li>{{ $label }}</li>
    @endforeach
    </ul>
@else
<p>Toutes les pièces requises ont été fournies.</p>
@endif

<p>Merci de compléter votre dossier dès que possible afin de poursuivre le processus.</p>

<p>Cordialement,<br>L'équipe IESCA</p>


