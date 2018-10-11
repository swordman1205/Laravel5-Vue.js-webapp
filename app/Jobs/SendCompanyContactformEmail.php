<?php

namespace App\Jobs;

use App\Course;
use App\CourseDay;
use App\Mail\CompanyReservation;
use App\Mail\CompanyConcactformAdminEmail;
use App\Mail\CompanyConcactformEmail;
use App\Mail\SportsmanReservation;
use App\Reservation;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendCompanyContactformEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $email;
    private $message;
    private $company;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($company, $email, $message)
    {
        $this->email = $email;
        $this->message = $message;
        $this->company = $company;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->email)->send(new CompanyConcactformEmail($this->company, $this->email, $this->message));
        Mail::send(new CompanyConcactformAdminEmail($this->company, $this->email, $this->message));
    }
}
