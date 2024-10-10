<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TwoFactorCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $twoFactorCode;

    public function __construct($twoFactorCode)
    {
        $this->twoFactorCode = $twoFactorCode;
    }

    public function build()
    {
        return $this->view('emails.sendTwoFactorCode')
                    ->with(['code' => $this->twoFactorCode]);
    }
}
