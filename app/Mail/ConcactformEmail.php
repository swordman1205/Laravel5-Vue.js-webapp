<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConcactformEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $email;

    public $message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $message)
    {
        $this->email = $email;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Richiesta Informazioni Orangogo";

        return $this->markdown('emails.contactform.user')
            ->from("info@orangogo.it")
            ->subject($subject)
            ->with([

            ]);
    }
}
