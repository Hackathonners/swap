<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Judite\Models\User;
use App\Judite\Models\Student;
use App\Judite\Models\Settings;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SettingsTest extends TestCase
{
    use DatabaseTransactions;

    public function testUpdateSettings()
    {
        // Prepare
        $admin = factory(User::class)->states('admin')->create();
        $settings = Settings::create();

        $enrollmentsStart = Carbon::tomorrow();
        $enrollmentsEnd = Carbon::tomorrow()->addDays(1);
        $exchangesStart = Carbon::tomorrow()->addDays(2);
        $exchangesEnd = Carbon::tomorrow()->addDays(3);
        $requestData = [
            'exchanges_start_at' => $exchangesStart,
            'exchanges_end_at' => $exchangesEnd,
            'enrollments_start_at' => $enrollmentsStart,
            'enrollments_end_at' => $enrollmentsEnd,
        ];

        // Execute
        $response = $this->actingAs($admin)
                         ->put(route('settings.update'), $requestData);

        // Assert
        $response->assertStatus(200);
        $actualSettings = Settings::first();
        $this->assertEquals($exchangesStart, $actualSettings->exchanges_start_at);
        $this->assertEquals($exchangesEnd, $actualSettings->exchanges_end_at);
        $this->assertEquals($enrollmentsStart, $actualSettings->enrollments_start_at);
        $this->assertEquals($enrollmentsEnd, $actualSettings->enrollments_end_at);
    }

    public function testUpdateSettingsWithInvalidEnrollemntPeriodRange()
    {
        // Prepare
        $admin = factory(User::class)->states('admin')->create();
        $settings = Settings::create();

        $enrollmentsStart = Carbon::tomorrow()->addDays(3);
        $enrollmentsEnd = Carbon::tomorrow();
        $exchangesStart = Carbon::tomorrow();
        $exchangesEnd = Carbon::tomorrow()->addDays(5);
        $requestData = [
            'exchanges_start_at' => $exchangesStart,
            'exchanges_end_at' => $exchangesEnd,
            'enrollments_start_at' => $enrollmentsStart,
            'enrollments_end_at' => $enrollmentsEnd,
        ];

        // Execute
        $response = $this->actingAs($admin)
                         ->put(route('settings.update'), $requestData);

        // Assert
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['enrollments_end_at']);
        $actualSettings = Settings::first();
        $this->assertEquals($settings->exchanges_start_at, $actualSettings->exchanges_start_at);
        $this->assertEquals($settings->exchanges_end_at, $actualSettings->exchanges_end_at);
        $this->assertEquals($settings->enrollments_start_at, $actualSettings->enrollments_start_at);
        $this->assertEquals($settings->enrollments_end_at, $actualSettings->enrollments_end_at);
    }

    public function testUpdateSettingsWithExchangePeriodBeforeEnrollmentPeriod()
    {
        // Prepare
        $admin = factory(User::class)->states('admin')->create();
        $settings = Settings::create();

        $enrollmentsStart = Carbon::tomorrow();
        $enrollmentsEnd = Carbon::tomorrow()->addDays(3);
        $exchangesStart = Carbon::tomorrow();
        $exchangesEnd = Carbon::tomorrow()->addDays(5);
        $requestData = [
            'exchanges_start_at' => $exchangesStart,
            'exchanges_end_at' => $exchangesEnd,
            'enrollments_start_at' => $enrollmentsStart,
            'enrollments_end_at' => $enrollmentsEnd,
        ];

        // Execute
        $response = $this->actingAs($admin)
                         ->put(route('settings.update'), $requestData);
        // Assert
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['exchanges_start_at']);
        $actualSettings = Settings::first();
        $this->assertEquals($settings->exchanges_start_at, $actualSettings->exchanges_start_at);
        $this->assertEquals($settings->exchanges_end_at, $actualSettings->exchanges_end_at);
        $this->assertEquals($settings->enrollments_start_at, $actualSettings->enrollments_start_at);
        $this->assertEquals($settings->enrollments_end_at, $actualSettings->enrollments_end_at);
    }

    public function testUpdateSettingsWithInvalidExchangePeriodRange()
    {
        // Prepare
        $admin = factory(User::class)->states('admin')->create();
        $settings = Settings::create();

        $enrollmentsStart = Carbon::tomorrow()->addDays(2);
        $enrollmentsEnd = Carbon::tomorrow()->addDays(3);
        $exchangesStart = Carbon::today()->addDays(5);
        $exchangesEnd = Carbon::tomorrow()->addDays(1);
        $requestData = [
            'exchanges_start_at' => $exchangesStart,
            'exchanges_end_at' => $exchangesEnd,
            'enrollments_start_at' => $enrollmentsStart,
            'enrollments_end_at' => $enrollmentsEnd,
        ];

        // Execute
        $response = $this->actingAs($admin)
                         ->put(route('settings.update'), $requestData);

        // Assert
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['exchanges_end_at']);
        $actualSettings = Settings::first();
        $this->assertEquals($settings->exchanges_start_at, $actualSettings->exchanges_start_at);
        $this->assertEquals($settings->exchanges_end_at, $actualSettings->exchanges_end_at);
        $this->assertEquals($settings->enrollments_start_at, $actualSettings->enrollments_start_at);
        $this->assertEquals($settings->enrollments_end_at, $actualSettings->enrollments_end_at);
    }

    public function testStudentCannotUpdateSettings()
    {
        // Prepare
        $user = factory(User::class)->create();
        factory(Student::class)->create(['user_id' => $user->id]);
        $settings = Settings::create();

        $exchangesStart = Carbon::today();
        $exchangesEnd = Carbon::tomorrow();
        $enrollmentsStart = Carbon::tomorrow()->addDays(2);
        $enrollmentsEnd = Carbon::tomorrow()->addDays(3);
        $requestData = [
            'exchanges_start_at' => $exchangesStart,
            'exchanges_end_at' => $exchangesEnd,
            'enrollments_start_at' => $enrollmentsStart,
            'enrollments_end_at' => $enrollmentsEnd,
        ];

        // Execute
        $response = $this->actingAs($user)
                         ->put(route('settings.update'), $requestData);

        // Assert
        $response->assertStatus(403);
        $actualSettings = Settings::first();
        $this->assertEquals($settings->exchanges_start_at, $actualSettings->exchanges_start_at);
        $this->assertEquals($settings->exchanges_end_at, $actualSettings->exchanges_end_at);
        $this->assertEquals($settings->enrollments_start_at, $actualSettings->enrollments_start_at);
        $this->assertEquals($settings->enrollments_end_at, $actualSettings->enrollments_end_at);
    }
}
