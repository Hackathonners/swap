<?php

namespace Tests\Feature\Registry;

use Tests\TestCase;
use App\Judite\Models\User;
use App\Judite\Models\Student;
use App\Judite\Models\Exchange;
use App\Judite\Models\Enrollment;
use App\Judite\Registry\EloquentExchangeRegistry;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EloquentExchangeRegistryTest extends TestCase
{
    use DatabaseTransactions;

    public function testHistoryOfStudent()
    {
        // Prepare
        $admin = factory(User::class)->states('admin')->create();
        $student = factory(Student::class)->create();
        $exchangeRegistry = new EloquentExchangeRegistry();
        $enrollment = factory(Enrollment::class)->create([
            'student_id' => $student->id,
        ]);
        $exchange = factory(Exchange::class)->create([
            'from_enrollment_id' => $enrollment->id,
        ]);
        $exchangeRegistry->record($exchange->fromEnrollment, $exchange->toEnrollment);

        // Execute
        $response = $this->actingAs($admin)
                    ->get(route('students.show', $student->id));

        // Assert
        $response->assertStatus(200);
        $response->assertViewHas('proposedExchanges');
        $response->assertViewHas('requestedExchanges');
        $response->assertViewHas('historyExchanges');
        $response->assertViewHas('student');
        $response->assertViewHas('enrollments');
    }
}
