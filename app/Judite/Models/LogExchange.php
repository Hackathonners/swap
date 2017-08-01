<?php

namespace App\Judite\Models;

use Illuminate\Database\Eloquent\Model;

class LogExchange extends Model
{
    /**
     * Get source shift of this logged exchange.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fromShift()
    {
        return $this->belongsTo(Shift::class, 'from_shift_id');
    }

    /**
     * Get target shift of this logged exchange.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function toShift()
    {
        return $this->belongsTo(Shift::class, 'to_shift_id');
    }

    /**
     * Get source student of this logged exchange.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fromStudent()
    {
        return $this->belongsTo(Student::class, 'from_student_id');
    }

    /**
     * Get target student of this logged exchange.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function toStudent()
    {
        return $this->belongsTo(Student::class, 'to_student_id');
    }
}
