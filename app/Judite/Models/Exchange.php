<?php

namespace App\JuditeModels;

use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    /**
     * Get academic year of this exchange.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
