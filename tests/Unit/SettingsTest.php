<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use App\Judite\Models\Settings;

class SettingsTest extends TestCase
{
    public function testExchangePeriodIsActive()
    {
        // Prepare
        $settings = new Settings;

        // Execute
        $settings->exchanges_start_at = Carbon::yesterday();
        $settings->exchanges_end_at = Carbon::tomorrow();

        //Assert
        $this->assertTrue($settings->withinExchangePeriod());
    }

    public function testExchangePeriodIsNotActive()
    {
        // Prepare
        $settings = new Settings;

        // Execute
        $settings->exchanges_start_at = Carbon::tomorrow();
        $settings->exchanges_end_at = Carbon::tomorrow()->addDays(2);

        // Assert
        $this->assertFalse($settings->withinExchangePeriod());
    }

    public function testExchangePeriodExpired()
    {
        // Prepare
        $settings = new Settings;

        // Execute
        $settings->exchanges_start_at = Carbon::yesterday()->subDays(2);
        $settings->exchanges_end_at = Carbon::yesterday();

        // Assert
        $this->assertFalse($settings->withinExchangePeriod());
    }
}
