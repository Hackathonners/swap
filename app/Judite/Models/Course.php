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
}
