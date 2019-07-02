<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Judite\Models\User;
use App\Judite\Models\Student;
use App\Judite\Models\Exchange;
use App\Judite\Models\Enrollment;
use Illuminate\Support\Facades\Mail;
use App\Judite\Models\ExchangeQueueEntry;
use App\Mail\ConfirmedExchangeNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Judite\Models\ExchangeRegistryEntry;

class SolverExchangeTest extends TestCase
{
    use RefreshDatabase;

    protected $exchange;
    protected $fromEnrollment;
    protected $toEnrollment;

    public function setUp()
    {
        parent::setUp();
        Mail::fake();
        $this->enableExchangesPeriod();
        $this->exchange = factory(Exchange::class)->create();
        $this->fromEnrollment = $this->exchange->fromEnrollment;
        $this->toEnrollment = $this->exchange->toEnrollment;
    }

    /** @test */
    public function a_student_can_submit_a_service_exchange()
    {
        $response = $this->actingAs($this->fromEnrollment->student->user)
                         ->post(route('exchanges.solver.store', $this->fromEnrollment->id), [
                             'to_shift_id' => $this->toEnrollment->shift_id
                         ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertEquals(1, ExchangeQueueEntry::count());
    }

    /** @test */
    public function service_has_solved_exchanges()
    {
        $this->actingAs($this->fromEnrollment->student->user)
            ->post(route('exchanges.solver.store', $this->fromEnrollment->id), [
                'to_shift_id' => $this->toEnrollment->shift_id
            ]);

        $this->actingAs($this->toEnrollment->student->user)
            ->post(route('exchanges.solver.store', $this->toEnrollment->id), [
                'to_shift_id' => $this->fromEnrollment->shift_id
            ]);

            $this->assertEnrollmentsChanged();
            $this->assertEquals(0, ExchangeQueueEntry::count());
            $this->assertEquals(1, ExchangeRegistryEntry::count());
    }

    /*
     * Assert enrollments remain unchanged.
     */
    protected function assertEnrollmentsRemainUnchanged()
    {
        $actualFromEnrollment = $this->fromEnrollment->fresh();
        $actualToEnrollment = $this->toEnrollment->fresh();
        $this->assertEquals($this->fromEnrollment->shift->id, $actualFromEnrollment->shift->id);
        $this->assertEquals($this->toEnrollment->shift->id, $actualToEnrollment->shift->id);
    }

    /*
     * Assert enrollments changed.
     */
    protected function assertEnrollmentsChanged()
    {
        $actualFromEnrollment = $this->fromEnrollment->fresh();
        $actualToEnrollment = $this->toEnrollment->fresh();
        $this->assertEquals($this->fromEnrollment->shift->id, $actualToEnrollment->shift->id);
        $this->assertEquals($this->toEnrollment->shift->id, $actualFromEnrollment->shift->id);
    }
}
