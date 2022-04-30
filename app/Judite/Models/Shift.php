<?php

namespace App\Judite\Models;

use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;
use App\Judite\Presenters\ShiftPresenter;


class Shift extends Model
{
    use PresentableTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['tag'];
    /**
     * The presenter for this entity.
     *
     * @var string
     */
    protected $presenter = ShiftPresenter::class;

    /**
     * Get course of this shift.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

}
