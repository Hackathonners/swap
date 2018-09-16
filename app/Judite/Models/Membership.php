<?php

namespace App\Judite\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['student', 'group'];

    /**
     * Scope a query to filter memberships by owner.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \App\Judite\Models\Student            $student
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOwnedBy($query, Student $student)
    {
        return $query->whereStudentId($student->id);
    }

    /**
     * Scope a query to order memberships by students number.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByStudent($query)
    {
        return $query->select('memberships.*')
            ->join('students', 'memberships.student_id', '=', 'students.id')
            ->orderBy('students.student_number', 'asc');
    }

    /**
     * Get student of this membership.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get group of this membership.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
