<?php

namespace App\Judite\Models;

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
    public function fromShift()
    {
        return $this->fromShiftRelation()->first();
    }

    /**
     * {@inheritdoc}
     */
    public function toShift()
    {
        return $this->toShiftRelation()->first();
    }

    /**
     * {@inheritdoc}
     */
    public function fromStudent()
    {
        return $this->fromStudentRelation()->first();
    }

    /**
     * {@inheritdoc}
     */
    public function toStudent()
    {
        return $this->toStudentRelation()->first();
    }

    /**
     * {@inheritdoc}
     */
    public function course()
    {
        return $this->toShift->course;
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
