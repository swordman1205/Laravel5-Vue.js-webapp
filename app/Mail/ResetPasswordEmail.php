<?php

namespace App\Mail;

use App\Course;
use App\CourseDay;
use App\Reservation;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $token)
    {
        $this->token = $token;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "OrangoGo - Reset Password";

        return $this->markdown('emails.reset_password')
            ->from("info@orangogo.it")
            ->to($this->user->email)
            ->subject($subject)
            ->with([
                "user "=> $this->user,
                'token' => $this->token
            ]);
    }
}
