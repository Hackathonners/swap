<?php

namespace App\Judite\Presenters;

use Laracasts\Presenter\Presenter;

class ShiftPresenter extends Presenter
{
    /**
     * Get the string representation of the shift.
     *
     * @return string
     */
    public function inlineToString()
    {
        return $this->entity->tag;
    }
}