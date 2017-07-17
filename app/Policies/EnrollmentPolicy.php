<?php

namespace App\Policies;

use App\Judite\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EnrollmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can export enrollments.
     *
     * @param  \App\Judite\Models\User  $user
     * @return bool
     */
    public function export(User $user)
    {
        return $user->isAdmin();
    }
}
