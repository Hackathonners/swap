<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Judite\Models\User;
use App\Judite\Models\Student;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    public function testUserIsAdmin()
    {
        // Prepare
        $user = factory(User::class)->states('admin')->create();

        // Assert
        $this->assertTrue($user->isAdmin());
    }

    public function testUserIsNotAdmin()
    {
        // Prepare
        $student = factory(Student::class)->create();

        // Assert
        $this->assertFalse($student->user->isAdmin());
    }

    public function testUserIsStudent()
    {
        // Prepare
        $user = factory(User::class)->create();
        factory(Student::class)->create(['user_id' => $user->id]);

        // Assert
        $this->assertTrue($user->isStudent());
    }

    public function testUserIsNotStudent()
    {
        // Prepare
        $user = factory(User::class)->create();

        // Assert
        $this->assertFalse($user->isStudent());
    }
}
