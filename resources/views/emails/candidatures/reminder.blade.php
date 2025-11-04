@component('emails.layouts.base')
<h2 style="margin:0 0 10px;">Rappel: pièces manquantes</h2>
<p style="margin:0 0 12px;">Bonjour {{ $candidature->user->name }},</p>
<p style="margin:0 0 12px;">Nous avons bien reçu votre candidature. Il manque toutefois les pièces suivantes :</p>

@if(count($missing))
<ul style="margin:0 0 12px; padding-left:18px;">
    @foreach($missing as $label)
        <li>{{ $label }}</li>
    @endforeach
    </ul>
@else
<p style="margin:0 0 12px;">Toutes les pièces requises ont été fournies.</p>
@endif

<p style="margin:0;">Merci de compléter votre dossier dès que possible afin de poursuivre le processus.</p>
@endcomponent


