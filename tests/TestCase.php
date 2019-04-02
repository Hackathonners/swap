<?php

namespace Tests;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

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
     * Enable groups period.
     */
    protected function enableGroupsPeriod()
    {
        $settings = app('settings');
        $settings->min_group_members = 2;
        $settings->max_group_members = 4;
        $settings->groups_start_at = Carbon::yesterday();
        $settings->groups_end_at = Carbon::tomorrow();
        $settings->save();
    }
}
