<?php

namespace App\Judite\Models;

use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    /**
     * Get shift of this exchange.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}
