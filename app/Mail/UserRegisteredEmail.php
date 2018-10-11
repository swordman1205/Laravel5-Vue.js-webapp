<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserRegisteredEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public $emailToken;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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

        return $this->markdown('emails.register.user')
            ->from("info@orangogo.it")
            ->subject($subject)
            ->with([
                'name' => $name,
            ]);
    }
}
