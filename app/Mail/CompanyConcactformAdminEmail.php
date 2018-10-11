<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CompanyConcactformAdminEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $message;
    private $company;

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
        $subject = "Richiesta informazioni per " . $this->company->name;

        return $this->markdown('emails.company_contactform.admin')
            ->from("info@orangogo.it")
            ->to($this->company->registrant->email)
            ->cc("info@orangogo.it")
            ->subject($subject)
            ->with([
                "company" => $this->company,
                "email" => $this->email,
                "message" => $this->message
            ]);
    }
}
