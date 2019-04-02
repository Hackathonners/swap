<?php

namespace App\Exceptions;

use Exception;

class GroupIsFullException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @param string $message
     */
    public function __construct($message = 'The group is already full.')
    {
        parent::__construct($message);
    }
}
