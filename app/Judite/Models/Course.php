<?php

namespace App\Judite\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    /**
     * Get shifts of this course.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shifts()
    {
        return $this->hasMany(Shift::class);
    }

    /**
     * Add shift to this course.
     *
     * @param  \App\Judite\Models\Shift  $shift
     * @return $this
     */
    public function addShift(Shift $shift)
    {
        $this->shifts()->save($shift);

        return $this;
    }

    /**
     * Get shift of this course by tag.
     *
     * @param string $tag
     * @return \App\Judite\Models\Shift|null
     */
    public function getShiftByTag($tag)
    {
        return $this->shifts()->where('tag', $tag)->first();
    }
}
