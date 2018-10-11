<?php

namespace App\Jobs;

use App\Course;
use App\CourseDay;
use App\Mail\CompanyReservation;
use App\Mail\ConcactformAdminEmail;
use App\Mail\ConcactformEmail;
use App\Mail\SportsmanReservation;
use App\Reservation;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendContactformEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $email;
    private $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $message)
    {
        $this->email = $email;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->email)->send(new ConcactformEmail($this->email, $this->message));
        Mail::send(new ConcactformAdminEmail($this->email, $this->message));
    }
}
