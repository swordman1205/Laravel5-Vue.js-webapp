<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CompanyConcactformEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $company;
    public $email;
    public $message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($company, $email, $message)
    {
        $this->company = $company;
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
        $subject = "Richiesta Informazioni Società Sportiva Orangogo";

        return $this->markdown('emails.company_contactform.user')
            ->from("info@orangogo.it")
            ->replyTo($this->company->registrant->email)
            ->subject($subject)
            ->with([
                "company" => $this->company
            ]);
    }
}
