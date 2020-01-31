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

    protected $admin;
    protected $settings;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin = factory(User::class)->states('admin')->create();
        $this->settings = Settings::create();
    }

    /** @test */
    public function an_admin_can_update_settings()
    {
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

        $response = $this->actingAs($this->admin)
            ->put(route('settings.update'), $requestData);

        $response->assertRedirect();
        $this->settings->refresh();
        $this->assertEquals($exchangesStart, $this->settings->exchanges_start_at);
        $this->assertEquals($exchangesEnd, $this->settings->exchanges_end_at);
        $this->assertEquals($enrollmentsStart, $this->settings->enrollments_start_at);
        $this->assertEquals($enrollmentsEnd, $this->settings->enrollments_end_at);
    }

    /** @test */
    public function settings_may_not_be_updated_with_an_invalid_exchanges_period()
    {
        $requestData = [
            'exchanges_start_at' => Carbon::tomorrow()->addDays(3),
            'exchanges_end_at' => Carbon::tomorrow(),
            'enrollments_start_at' => Carbon::tomorrow(),
            'enrollments_end_at' => Carbon::tomorrow()->addDays(5),
        ];

        $response = $this->actingAs($this->admin)
            ->put(route('settings.update'), $requestData);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['exchanges_end_at']);
        $this->assertSettingsRemainUnchanged();
    }

    /** @test */
    public function settings_can_be_updated_with_an_exchanges_period_before_enrollments_period()
    {
        $exchangesStart = Carbon::tomorrow();
        $exchangesEnd = Carbon::tomorrow()->addDays(3);
        $enrollmentsStart = Carbon::tomorrow();
        $enrollmentsEnd = Carbon::tomorrow()->addDays(5);
        $requestData = [
            'exchanges_start_at' => $exchangesStart,
            'exchanges_end_at' => $exchangesEnd,
            'enrollments_start_at' => $enrollmentsStart,
            'enrollments_end_at' => $enrollmentsEnd,
        ];

        $response = $this->actingAs($this->admin)
            ->put(route('settings.update'), $requestData);

        $response->assertRedirect();
        $response->assertRedirect();
        $this->settings->refresh();
        $this->assertEquals($exchangesStart, $this->settings->exchanges_start_at);
        $this->assertEquals($exchangesEnd, $this->settings->exchanges_end_at);
        $this->assertEquals($enrollmentsStart, $this->settings->enrollments_start_at);
        $this->assertEquals($enrollmentsEnd, $this->settings->enrollments_end_at);
    }

    /** @test */
    public function settings_may_not_be_updated_with_an_invalid_enrollments_period()
    {
        $requestData = [
            'exchanges_start_at' => Carbon::tomorrow()->addDays(2),
            'exchanges_end_at' => Carbon::tomorrow()->addDays(3),
            'enrollments_start_at' => Carbon::tomorrow()->addDays(5),
            'enrollments_end_at' => Carbon::tomorrow()->addDays(1),
        ];

        $response = $this->actingAs($this->admin)
            ->put(route('settings.update'), $requestData);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['enrollments_end_at']);
        $this->assertSettingsRemainUnchanged();
    }

    /** @test */
    public function students_may_not_see_settings_form()
    {
        $student = factory(Student::class)->create();

        $response = $this->actingAs($student->user)
            ->put(route('settings.edit'));

        $response->assertStatus(404);
    }

    /** @test */
    public function students_may_not_update_settings()
    {
        $student = factory(Student::class)->create();
        $requestData = [
            'exchanges_start_at' => Carbon::today(),
            'exchanges_end_at' => Carbon::tomorrow(),
            'enrollments_start_at' => Carbon::tomorrow()->addDays(2),
            'enrollments_end_at' => Carbon::tomorrow()->addDays(3),
        ];

        $response = $this->actingAs($student->user)
            ->put(route('settings.update'), $requestData);

        $response->assertStatus(404);
        $this->assertSettingsRemainUnchanged();
    }

    /** @test */
    public function unauthenticated_users_may_not_update_settings()
    {
        $requestData = [
            'exchanges_start_at' => Carbon::today(),
            'exchanges_end_at' => Carbon::tomorrow(),
            'enrollments_start_at' => Carbon::tomorrow()->addDays(2),
            'enrollments_end_at' => Carbon::tomorrow()->addDays(3),
        ];

        $response = $this->put(route('settings.update'), $requestData);

        $response->assertRedirect(route('login'));
        $this->assertSettingsRemainUnchanged();
    }

    /*
     * Assert settings remain unchanged.
     */
    protected function assertSettingsRemainUnchanged()
    {
        $actualSettings = $this->settings->fresh();
        $this->assertEquals($this->settings->exchanges_start_at, $actualSettings->exchanges_start_at);
        $this->assertEquals($this->settings->exchanges_end_at, $actualSettings->exchanges_end_at);
        $this->assertEquals($this->settings->enrollments_start_at, $actualSettings->enrollments_start_at);
        $this->assertEquals($this->settings->enrollments_end_at, $actualSettings->enrollments_end_at);
    }
}
