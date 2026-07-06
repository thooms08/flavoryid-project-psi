<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifikasiEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $kode;
    public $nama;

    public function __construct($kode, $nama)
    {
        $this->kode = $kode;
        $this->nama = $nama;
    }

    public function build()
    {
        return $this->subject('Kode Verifikasi Partner Flavory.id')
                    ->view('emails.verifikasi');
    }
}