<?php

namespace App\Judite\Presenters;

use NumberFormatter;
use Laracasts\Presenter\Presenter;

class CoursePresenter extends Presenter
{
    /**
     * The year of this course in ordinal format.
     *
     * @return string
     */
    public function getOrdinalYear()
    {
        $formatter = new NumberFormatter(app()->getLocale(), NumberFormatter::SPELLOUT);
        $formatter->setTextAttribute(NumberFormatter::DEFAULT_RULESET, '%spellout-ordinal');

        return ucfirst($formatter->format($this->entity->year));
    }

    /**
     * The semester of this course in ordinal format.
     *
     * @return string
     */
    public function getOrdinalSemester()
    {
        $formatter = new NumberFormatter(app()->getLocale(), NumberFormatter::ORDINAL);

        return ucfirst($formatter->format($this->entity->semester));
    }
}
