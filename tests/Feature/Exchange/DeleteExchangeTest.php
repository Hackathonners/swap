<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Judite\Models\Student;
use App\Judite\Models\Exchange;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteExchangeTest extends TestCase
{
    use DatabaseTransactions;

    protected $exchange;
    protected $fromEnrollment;
    protected $toEnrollment;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableExchangesPeriod();
        $this->exchange = factory(Exchange::class)->create();
        $this->fromEnrollment = $this->exchange->fromEnrollment;
        $this->toEnrollment = $this->exchange->toEnrollment;
    }

    /** @test */
    public function a_student_can_delete_a_shift_exchange_request()
    {
        $response = $this->actingAs($this->fromEnrollment->student->user)
            ->delete(route('exchanges.destroy', $this->exchange->id));

        $response->assertRedirect();
        $this->assertEquals(0, Exchange::count());
    }

    /** @test */
    public function a_student_may_not_delete_a_shift_exchange_request_of_another_student()
    {
        $unauthorizedStudent = factory(Student::class)->create();

        $response = $this->actingAs($unauthorizedStudent->user)
            ->delete(route('exchanges.destroy', $this->exchange->id));

        $response->assertStatus(404);
        $this->assertEquals(1, Exchange::count());
    }

    /** @test */
    public function unauthenticated_users_may_not_delete_exchanges()
    {
        $response = $this->delete(route('exchanges.destroy', $this->exchange->id));

        $response->assertRedirect(route('login'));
        $this->assertEquals(1, Exchange::count());
    }
}
