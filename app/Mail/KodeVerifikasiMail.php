<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class KodeVerifikasiMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct($code, $user)
    {
        $this->code = $code;
        $this->user = $user;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Kode Verifikasi Login - Flavory.id')
                    ->view('emails.kode-verifikasi'); 
    }
}