<?php

namespace App\Mail;

use App\Company;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CompanyRegisteredEmail extends Mailable
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
        $name = "{$this->user->first_name} {$this->user->last_name}";
        $subject = "Benvenuto su Orangogo";

        return $this->markdown('emails.register.company')
            ->from("info@orangogo.it")
            ->subject($subject)
            ->with([
                'name' => $name,
            ]);
    }
}
