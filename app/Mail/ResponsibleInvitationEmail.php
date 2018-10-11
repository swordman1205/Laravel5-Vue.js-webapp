<?php

namespace App\Mail;

use App\Course;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResponsibleInvitationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public $emailToken;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Course $course)
    {
        $this->course = $course;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Invito a gestire Corso Orangogo";

        return $this->markdown('emails.responsible_invitation')
            ->from("info@orangogo.it")
            ->to($this->course->responsible_email)
            ->subject($subject)
            ->with([
                'course' => $this->course
            ]);
    }
}
