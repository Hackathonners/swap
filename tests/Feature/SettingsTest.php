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

    public function testUpdateExchangePeriodSettings()
    {
        // Prepare
        $admin = factory(User::class)->states('admin')->create();
        $settings = new Settings;
        $settings->exchanges_start_at = Carbon::createFromTimestamp(0);
        $settings->exchanges_end_at = Carbon::createFromTimestamp(0);
        $settings->save();

        $start = Carbon::today();
        $end = Carbon::tomorrow();
        $requestData = [
            'exchanges_start_at' => $start,
            'exchanges_end_at' => $end,
        ];

        // Execute
        $response = $this->actingAs($admin)
                         ->put(route('settings.update'), $requestData);

        // Assert
        $response->assertStatus(200);
        $actualSettings = Settings::first();
        $this->assertEquals($start, $actualSettings->exchanges_start_at);
        $this->assertEquals($end, $actualSettings->exchanges_end_at);
    }

    public function testUpdateExchangePeriodSettingsWithInvalidRange()
    {
        // Prepare
        $admin = factory(User::class)->states('admin')->create();
        $settings = new Settings;
        $settings->exchanges_start_at = Carbon::createFromTimestamp(0);
        $settings->exchanges_end_at = Carbon::createFromTimestamp(0);
        $settings->save();

        $start = Carbon::today()->addDays(5);
        $end = Carbon::today()->addDays(1);

        // Execute
        $response = $this->actingAs($admin)
                         ->put(route('settings.update'), [
            'exchanges_start_at' => $start,
            'exchanges_end_at' => $end,
        ]);

        // Assert
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['exchanges_end_at']);
        $actualSettings = Settings::first();
        $this->assertEquals($settings->exchanges_start_at, $actualSettings->exchanges_start_at);
        $this->assertEquals($settings->exchanges_end_at, $actualSettings->exchanges_end_at);
    }

    public function testStudentCannotUpdateSettings()
    {
        // Prepare
        $user = factory(User::class)->create();
        factory(Student::class)->create(['user_id' => $user->id]);
        $settings = new Settings;
        $settings->exchanges_start_at = Carbon::createFromTimestamp(0);
        $settings->exchanges_end_at = Carbon::createFromTimestamp(0);
        $settings->save();

        $start = Carbon::today();
        $end = Carbon::tomorrow();
        $requestData = [
            'exchanges_start_at' => $start,
            'exchanges_end_at' => $end,
        ];

        // Execute
        $response = $this->actingAs($user)
                         ->put(route('settings.update'), $requestData);

        // Assert
        $response->assertStatus(403);
        $actualSettings = Settings::first();
        $this->assertEquals($settings->exchanges_start_at, $actualSettings->exchanges_start_at);
        $this->assertEquals($settings->exchanges_end_at, $actualSettings->exchanges_end_at);
    }
}
