<?php

namespace App\Mail;

use App\Company;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CompanyRegisteredAdminEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public $company;

    public $emailToken;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Company $company)
    {
        $this->user = $user;
        $this->company = $company;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Nuova Registrazione Società Sportiva su OrangoGo";

        return $this->markdown('emails.register.company_admin')
            ->from("info@orangogo.it")
            ->to("info@orangogo.it")
            ->subject($subject)
            ->with([
                'name' => $this->company->name,
                'email' => $this->user->email,
            ]);
    }
}
