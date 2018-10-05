<?php

namespace App\Exceptions;

use Exception;
use App\Judite\Models\Group;

class UserHasAlreadyAnInviteInGroupException extends Exception
{
    /**
     * Group where User has already an Invite.
     *
     * @var \App\Judite\Models\Group
     */
    protected $group;

    /**
     * Create a new exception instance.
     *
     * @param \App\Judite\Models\Group $group
     * @param string                   $message
     */
    public function __construct(Group $group = null, $message = 'User already has an invite in group.')
    {
        parent::__construct($message);
        $this->group = $group;
    }

    /**
     * Get the group of this exception.
     *
     * @return \App\Judite\Models\Group
     */
    public function getGroup()
    {
        return $this->group;
    }
}
