<?php

namespace App\Mail;

use App\Course;
use App\CourseDay;
use App\Reservation;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SportsmanReservationEmail extends Mailable
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
        $subject = "La tua prenotazione OrangoGo";

        return $this->markdown('emails.reservation.sportsman')
            ->from("info@orangogo.it")
            ->cc("info@orangogo.it")
            ->subject($subject)
            ->with([
                'sportsman' => $name,
                'course' => $this->course,
                'reservation' => $this->reservation
            ]);

    }
}
