<?php

namespace App\Policies;

use App\User;
use App\Course;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursePolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return true /*$user->isCompanyOwner()*/;
    }

    public function update(User $user, Course $course)
    {
        return $user->isCompanyMember($course->company_id);
    }
}
