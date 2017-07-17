<?php

namespace App\Policies;

use App\Judite\Models\User;
use App\Judite\Models\Exchange;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExchangePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can confirm an enrollment.
     *
     * @param  \App\Judite\Models\User  $user
     * @param  \App\Judite\Models\Exchange  $exchange
     * @return bool
     */
    public function confirm(User $user, Exchange $exchange)
    {
        return $user->id === $exchange->toEnrollment->student->user_id;
    }
}
