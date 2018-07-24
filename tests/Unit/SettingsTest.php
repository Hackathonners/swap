<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use App\Judite\Models\Settings;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SettingsTest extends TestCase
{
    use DatabaseTransactions;

    public function testExchangePeriodIsActive()
    {
        // Prepare
        $settings = new Settings();

        // Execute
        $settings->exchanges_start_at = Carbon::yesterday();
        $settings->exchanges_end_at = Carbon::tomorrow();

        //Assert
        $this->assertTrue($settings->withinExchangePeriod());
    }

    public function testExchangePeriodIsNotActive()
    {
        // Prepare
        $settings = new Settings();

        // Execute
        $settings->exchanges_start_at = Carbon::tomorrow();
        $settings->exchanges_end_at = Carbon::tomorrow()->addDays(2);

        // Assert
        $this->assertFalse($settings->withinExchangePeriod());
    }

    public function testExchangePeriodExpired()
    {
        // Prepare
        $settings = new Settings();

        // Execute
        $settings->exchanges_start_at = Carbon::yesterday()->subDays(2);
        $settings->exchanges_end_at = Carbon::yesterday();

        // Assert
        $this->assertFalse($settings->withinExchangePeriod());
    }

    public function testEnrollmentPeriodIsActive()
    {
        // Prepare
        $settings = new Settings();

        // Execute
        $settings->enrollments_start_at = Carbon::yesterday();
        $settings->enrollments_end_at = Carbon::tomorrow();

        // Assert
        $this->assertTrue($settings->withinEnrollmentPeriod());
    }

    public function testEnrollmentPeriodIsNotActive()
    {
        // Prepare
        $settings = new Settings();

        // Execute
        $settings->enrollments_start_at = Carbon::tomorrow();
        $settings->enrollments_end_at = Carbon::tomorrow()->addDays(2);

        // Assert
        $this->assertFalse($settings->withinEnrollmentPeriod());
    }

    public function testEnrollmentPeriodExpired()
    {
        // Prepare
        $settings = new Settings();

        // Execute
        $settings->enrollments_start_at = Carbon::yesterday()->subDays(2);
        $settings->enrollments_end_at = Carbon::yesterday();

        // Assert
        $this->assertFalse($settings->withinEnrollmentPeriod());
    }

    public function testEnrollmentPeriodIsNotActiveWhenDatesAreNotSpecified()
    {
        // Prepare
        $settings = new Settings();

        // Assert
        $this->assertFalse($settings->withinEnrollmentPeriod());
    }

    public function testExchangePeriodIsNotActiveWhenDatesAreNotSpecified()
    {
        // Prepare
        $settings = new Settings();

        // Assert
        $this->assertFalse($settings->withinExchangePeriod());
    }

    public function testGroupCreationPeriodIsActive()
    {
        // Prepare
        $settings = new Settings();

        // Execute
        $settings->groups_creation_start_at = Carbon::yesterday();
        $settings->groups_creation_end_at = Carbon::tomorrow();

        //Assert
        $this->assertTrue($settings->withinGroupCreationPeriod());
    }

    public function testGroupCreationPeriodIsNotActive()
    {
        // Prepare
        $settings = new Settings();

        // Execute
        $settings->groups_creation_start_at = Carbon::tomorrow();
        $settings->groups_creation_end_at = Carbon::tomorrow()->addDays(2);

        // Assert
        $this->assertFalse($settings->withinGroupCreationPeriod());
    }

    public function testGroupCreationPeriodExpired()
    {
        // Prepare
        $settings = new Settings();

        // Execute
        $settings->groups_creation_start_at = Carbon::yesterday()->subDays(2);
        $settings->groups_creation_end_at = Carbon::yesterday();

        // Assert
        $this->assertFalse($settings->withinGroupCreationPeriod());
    }

    public function testGroupCreationPeriodIsNotActiveWhenDatesAreNotSpecified()
    {
        // Prepare
        $settings = new Settings();

        // Assert
        $this->assertFalse($settings->withinGroupCreationPeriod());
    }
}
