<?php

namespace App\Judite\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ExchangeQueueEntry extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'exchanges_queue';

    public function fromShift(): Shift
    {
        return $this->fromShiftRelation;
    }

    public function toShift(): Shift
    {
        return $this->toShiftRelation;
    }

    public function fromStudent(): Student
    {
        return $this->fromStudentRelation;
    }

    public function fromEnrollment(): Enrollment
    {
        return $this->fromEnrollmentRelation;
    }

    public function course(): Course
    {
        return $this->fromShiftRelation->course;
    }

    public function getDate(): Carbon
    {
        return $this->created_at;
    }

    /**
     * Get source shift of this recorded exchange.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fromShiftRelation()
    {
        return $this->belongsTo(Shift::class, 'from_shift_id');
    }

    /**
     * Get target shift of this recorded exchange.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function toShiftRelation()
    {
        return $this->belongsTo(Shift::class, 'to_shift_id');
    }

    /**
     * Get source student of this recorded exchange.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fromStudentRelation()
    {
        return $this->belongsTo(Student::class, 'from_student_id');
    }

    /**
     * Get source enrollment of this recorded exchange.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fromEnrollmentRelation()
    {
        return $this->belongsTo(Enrollment::class, 'from_enrollment_id');
    }

    public function toServiceFormat()
    {
        return [
            'id' => "$this->id",
            'from_shift_id' => $this->fromShift()->tag,
            'to_shift_id' => $this->toShift()->tag,
            'created_at' => $this->getDate()->day
        ];
    }
}
