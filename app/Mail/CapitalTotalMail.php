<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CapitalTotalMail extends Mailable
{
    use Queueable, SerializesModels;

    public $capitalTotal;

    public $fecha;

    public function __construct($capitalTotal, $fecha)
    {
        $this->capitalTotal = $capitalTotal;
        $this->fecha = $fecha;
    }

    public function build()
    {
        return $this->subject('📊 Capital Total Actualizado')
            ->markdown('emails.capital-total');
    }
}
