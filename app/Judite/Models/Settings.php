<?php

namespace App\Judite\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'exchanges_start_at',
        'exchanges_end_at',
        'enrollments_start_at',
        'enrollments_end_at',
        'groups_creation_start_at',
        'groups_creation_end_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'exchanges_start_at',
        'exchanges_end_at',
        'enrollments_start_at',
        'enrollments_end_at',
        'groups_creation_start_at',
        'groups_creation_end_at',
    ];

    /**
     * Checks whether today's date is within the shift exchanging period.
     *
     * @return bool
     */
    public function withinExchangePeriod(): bool
    {
        if (is_null($this->exchanges_start_at) || is_null($this->exchanges_end_at)) {
            return false;
        }

        return $this->exchanges_start_at->isPast() && ! $this->exchanges_end_at->isPast();
    }

    /**
     * Checks whether today's date is within the course enrollment period.
     *
     * @return bool
     */
    public function withinEnrollmentPeriod(): bool
    {
        if (is_null($this->enrollments_start_at) || is_null($this->enrollments_end_at)) {
            return false;
        }

        return $this->enrollments_start_at->isPast() && ! $this->enrollments_end_at->isPast();
    }

    /**
     * Checks whether today's date is within the group creation period.
     *
     * @return bool
     */
    public function withinGroupCreationPeriod(): bool
    {
        if (is_null($this->groups_creation_start_at) || is_null($this->groups_creation_end_at)) {
            return false;
        }

        return $this->groups_creation_start_at->isPast() && ! $this->groups_creation_end_at->isPast();
    }
}
