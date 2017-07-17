<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Judite\Models\User;
use App\Judite\Models\Student;

class UserTest extends TestCase
{
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
}
