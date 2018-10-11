<?php

namespace App\Jobs;

use App\Course;
use App\CourseDay;
use App\Mail\CompanyReservationEmail;
use App\Mail\ResetPasswordEmail;
use App\Mail\SportsmanReservationEmail;
use App\Reservation;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendResetPasswordEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $token;

    public $user;

    /**
     * SendResetPasswordEmail constructor.
     * @param User $user
     * @param $token
     */
    public function __construct(User $user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->user->email)->send(new ResetPasswordEmail($this->user, $this->token));
    }
}
