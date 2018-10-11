<?php

namespace App\Jobs;

use App\Course;
use App\Mail\ResponsibleInvitationEmail;
use App\User;
use App\Mail\UserRegisteredEmail;
use App\Mail\UserRegisteredAdminEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendResponsibleInvitationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $course;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Course $course)
    {
        $this->course = $course;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->course->responsible_email)->send(new ResponsibleInvitationEmail($this->course));
    }
}
