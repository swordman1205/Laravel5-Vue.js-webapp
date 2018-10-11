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

class CompanyReservationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $reservation;
    public $course;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Reservation $reservation, User $user, Course $course)
    {
        $this->user = $user;
        $this->reservation = $reservation;
        $this->course = $course;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $name = "{$this->reservation->first_name} {$this->reservation->last_name}";
        $subject = "OrangoGo - Uno sportivo ha prenotato una prima prova";

        return $this->markdown('emails.reservation.company')
            ->from("info@orangogo.it")
            ->cc("info@orangogo.it")
            ->subject($subject)
            ->with([
                'reservation' => $this->reservation,
                'sportsman' => $name,
                'birthday' => $this->reservation->birthday,
                'course' => $this->course,
                'user' => $this->user
            ]);
    }
}
