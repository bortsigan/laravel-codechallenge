<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class WelcomeEmail extends Mailable
{
    public $user;
    public $voucher;

    public function __construct($user, $voucher)
    {
        $this->user = $user;
        $this->voucher = $voucher;
    }

    public function build()
    {
        return $this->subject('Welcome! Here is your Voucher Code')
                    ->view('emails.welcome');
    }
}
