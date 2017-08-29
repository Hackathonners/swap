<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Judite\Models\User;
use App\Judite\Models\Student;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DashboardTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function a_student_can_access_student_dashboard()
    {
        $student = factory(Student::class)->create();

        $response = $this->actingAs($student->user)
            ->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas([
            'enrollments', 'proposedExchanges', 'requestedExchanges',
        ]);
    }

    /** @test */
    public function an_admin_can_access_admin_dashboard()
    {
        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)
            ->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('courses');
    }
}
