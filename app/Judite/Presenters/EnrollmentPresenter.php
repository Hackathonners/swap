<?php

namespace App\Judite\Presenters;

use Laracasts\Presenter\Presenter;

class EnrollmentPresenter extends Presenter
{
    /**
     * Get the shift tag of this enrollment.
     *
     * @param  string  $placeholder
     * @return string
     */
    public function getShiftTag($placeholder = '---')
    {
        return $this->entity->shift_id ? $this->entity->shift->tag : $placeholder;
    }
}
