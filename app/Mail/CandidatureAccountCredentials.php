<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CandidatureAccountCredentials extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $name, public string $email, public string $password)
    {
    }

    public function build()
    {
        return $this->subject('Votre compte IESCA a été créé')
            ->view('emails.candidatures.account-credentials');
    }
}


