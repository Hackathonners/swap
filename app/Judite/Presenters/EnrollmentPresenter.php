<?php

namespace App\Judite\Presenters;

use Laracasts\Presenter\Presenter;

class EnrollmentPresenter extends Presenter
{
    /**
     * Get the shift tag of this enrollment.
     *
     * @param string $placeholder
     *
     * @return string
     */
    public function getShiftTag($placeholder = '---')
    {
        return $this->entity->shift_id ? $this->entity->shift->tag : $placeholder;
    }

    /**
     * Get the formatted date of the last update of this enrollment.
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->entity->updated_at->toDayDateTimeString();
    }

    /**
     * Get the string representation of the enrollment.
     *
     * @return string
     */
    public function inlineToString()
    {
        return $this->entity->student->user->name
            .' ('.$this->entity->student->student_number.')'
            .' - '.$this->getShiftTag()
            .' on '.$this->entity->course->name;
    }
}
