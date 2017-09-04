<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LandingPageFormEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;

    public $email;

    public $company;

    public $formMessage;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $email, $company, $message)
    {
        $this->name = $name;

        $this->email = $email;

        $this->company = $company;

        $this->formMessage = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.landing-form');
    }
}
