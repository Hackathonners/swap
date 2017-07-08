<?php

namespace App\JuditeModels;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    /**
     * Get user who owns this student.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
