<?php

namespace App\Jobs;

use App\Course;
use App\CourseDay;
use App\Mail\CompanyReservationEmail;
use App\Mail\SportsmanReservationEmail;
use App\Reservation;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendReservationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $reservation;
    private $user;
    private $course;
    private $courseDay;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Reservation $reservation, User $user, Course $course)
    {
        $this->reservation = $reservation;
        $this->user = $user;
        $this->course = $course;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->user->email)->send(new SportsmanReservationEmail($this->reservation, $this->user, $this->course));

        if ($this->user->email !== $this->reservation->email && !empty($this->reservation->email)) {
            Mail::to($this->reservation->email)->send(new SportsmanReservationEmail($this->reservation, $this->user, $this->course));
        }
        Mail::to($this->course->company->email)->send(new CompanyReservationEmail($this->reservation, $this->user, $this->course));
    }
}
