<?php

namespace App\Judite\Registry;

use App\Judite\Models\Enrollment;
use App\Judite\Models\ExchangeRegistryEntry;
use App\Judite\Contracts\Registry\ExchangeRegistry;

class EloquentExchangeRegistry implements ExchangeRegistry
{
    /**
     * {@inheritdoc}
     */
    public function record(Enrollment $fromEnrollment, Enrollment $toEnrollment)
    {
        $logExchange = ExchangeRegistryEntry::make();
        $logExchange->fromShiftRelation()->associate($fromEnrollment->shift);
        $logExchange->toShiftRelation()->associate($toEnrollment->shift);
        $logExchange->fromStudentRelation()->associate($fromEnrollment->student);
        $logExchange->toStudentRelation()->associate($toEnrollment->student);
        $logExchange->save();
    }

    /**
     * {@inheritdoc}
     */
    public function paginate()
    {
        return ExchangeRegistryEntry::latest('id')->paginate();
    }

    /**
     * {@inheritdoc}
     */
    public function truncate()
    {
        ExchangeRegistryEntry::getQuery()->delete();
    }
}
