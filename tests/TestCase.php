<?php

namespace Tests;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /*
     * Enable enrollments period.
     */
    protected function enableEnrollmentsPeriod()
    {
        $settings = app('settings');
        $settings->enrollments_start_at = Carbon::yesterday();
        $settings->enrollments_end_at = Carbon::tomorrow();
        $settings->save();
    }

    /*
     * Enable exchanges period.
     */
    protected function enableExchangesPeriod()
    {
        $settings = app('settings');
        $settings->exchanges_start_at = Carbon::yesterday();
        $settings->exchanges_end_at = Carbon::tomorrow();
        $settings->save();
    }

    /*
     * Enable group creation period.
     */
    protected function enableGroupCreationPeriod()
    {
        $settings = app('settings');
        $settings->groups_creation_start_at = Carbon::yesterday();
        $settings->groups_creation_end_at = Carbon::tomorrow();
        $settings->save();
    }
}
