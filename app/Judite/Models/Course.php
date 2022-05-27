<?php

namespace App\Judite\Models;

use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;
use App\Judite\Presenters\CoursePresenter;

class Course extends Model
{
	use PresentableTrait;

	/**
	 * The presenter for this entity.
	 *
	 * @var string
	 */
	protected $presenter = CoursePresenter::class;

	/**
	 * Scope a query to order courses by year, semester and name.
	 *
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeOrderedList($query)
	{
		return $query->orderBy('year', 'asc')
			->orderBy('semester', 'asc')
			->orderBy('name', 'asc');
	}

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
	 * Get enrollments on this course.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function enrollments()
	{
		return $this->hasMany(Enrollment::class);
	}


	/**
	 * Add shift to this course.
	 *
	 * @param \App\Judite\Models\Shift $shift
	 *
	 * @return $this
	 */
	public function addShift(Shift $shift): self
	{
		$this->shifts()->save($shift);

		return $this;
	}

	/**
	 * Get shift of this course by tag.
	 *
	 * @param string $tag
	 *
	 * @return \App\Judite\Models\Shift|null
	 */
	public function getShiftByTag($tag): ?Shift
	{
		return $this->shifts()->where('tag', $tag)->first();
	}


	/**
	 * Get automatic exchanges of this course
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function automaticExchanges()
	{
        $toEnrollments = $this->enrollments()->whereNull('student_id')->pluck('id')->toArray();
        return Exchange::whereIn('to_enrollment_id',$toEnrollments)->lockForUpdate();
		//})->whereNull('to_enrollment_id')->lockForUpdate();
	}
}
