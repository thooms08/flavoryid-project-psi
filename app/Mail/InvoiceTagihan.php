<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceTagihan extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        $subject = $this->data['status'] === 'jatuh_tempo' 
            ? 'Peringatan Jatuh Tempo Langganan - Flavory.id' 
            : 'Pemberitahuan Penonaktifan Layanan - Flavory.id';

        return $this->subject($subject)
                    ->view('emails.invoice_tagihan');
    }
}