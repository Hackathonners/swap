<?php

namespace App\Judite\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Judite\Contracts\Registry\ExchangeRegistryEntry as ExchangeRegistryEntryContract;

class ExchangeRegistryEntry extends Model implements ExchangeRegistryEntryContract
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'log_exchanges';

    /**
     * {@inheritdoc}
     */
    public function fromShift(): Shift
    {
        return $this->fromShiftRelation;
    }

    /**
     * {@inheritdoc}
     */
    public function toShift(): Shift
    {
        return $this->toShiftRelation;
    }

    /**
     * {@inheritdoc}
     */
    public function fromStudent(): Student
    {
        return $this->fromStudentRelation;
    }

    /**
     * {@inheritdoc}
     */
    public function toStudent(): Student
    {
        return $this->toStudentRelation;
    }

    /**
     * {@inheritdoc}
     */
    public function course(): Course
    {
        return $this->fromShiftRelation->course;
    }

    /**
     * {@inheritdoc}
     */
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
     * Get target student of this recorded exchange.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function toStudentRelation()
    {
        return $this->belongsTo(Student::class, 'to_student_id');
    }
}
