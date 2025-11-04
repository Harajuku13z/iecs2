<?php

namespace App\Mail;

use App\Models\Candidature;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CandidatureReminder extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Candidature $candidature, public array $missing)
    {
    }

    public function build()
    {
        return $this->subject('Rappel: pièces manquantes pour votre candidature')
            ->view('emails.candidatures.reminder');
    }
}


