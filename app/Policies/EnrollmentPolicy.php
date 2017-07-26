<?php

namespace App\Policies;

use App\Judite\Models\User;
use App\Judite\Models\Enrollment;
use Illuminate\Auth\Access\HandlesAuthorization;

class EnrollmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can exchange an enrollment.
     *
     * @param  \App\Judite\Models\User  $user
     * @param  \App\Judite\Models\Enrollment  $enrollment
     * @return bool
     */
    public function exchange(User $user, Enrollment $enrollment)
    {
        return $user->id === $enrollment->student->user_id;
    }
}
