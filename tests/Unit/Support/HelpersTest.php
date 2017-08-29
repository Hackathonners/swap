<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Judite\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HelpersTest extends TestCase
{
    use DatabaseTransactions;

    public function testGetAuthenticatedStudent()
    {
        $student = factory(Student::class)->create();
        Auth::loginUsingId($student->user->id);

        $this->assertTrue(student()->is(Auth::user()->student));
    }
}
