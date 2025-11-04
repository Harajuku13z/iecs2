<?php

namespace App\Mail;

use App\Models\Candidature;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CandidatureStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Candidature $candidature)
    {
    }

    public function build()
    {
        return $this->subject('Mise à jour de votre candidature')
            ->view('emails.candidatures.status-updated');
    }
}


