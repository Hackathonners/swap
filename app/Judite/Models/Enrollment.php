<?php

namespace App\Judite\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['student', 'shift'];

    /**
     * Exchange shifts with the given enrollment.
     *
     * @param  \App\Judite\Models\Enrollment  $enrollment
     * @return $this
     */
    public function exchange(Enrollment $enrollment)
    {
        $fromShiftId = $this->shift_id;
        $this->shift()->associate($enrollment->shift_id);
        $enrollment->shift()->associate($fromShiftId);

        $this->save();
        $enrollment->save();

        return $this;
    }

    /**
     * Get exchanges of this enrollment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function exchanges()
    {
        return $this->hasMany(Exchange::class, 'from_enrollment_id');
    }

    /**
     * Get student of this enrollment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get course of this enrollment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get shift of this enrollment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}
