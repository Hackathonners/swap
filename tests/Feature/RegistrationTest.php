<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegistrationTest extends TestCase
{
    use DatabaseTransactions;

    public function testRegisterStudentWithValidEmail()
    {
        // Prepare
        $studentName = 'Marco Couto';
        $email = 'a12345@alunos.uminho.pt';
        $password = '123456';

        // Execute
        $response = $this->post(route('register'), [
            'name' => $studentName,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        // Assert
        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            'email' => $email,
        ]);
    }

    public function testRegisterStudentWithInvalidEmail()
    {
        // Prepare
        $studentName = 'Marco Couto';
        $email = 'marco@mail.com';
        $password = '123456';

        // Execute
        $response = $this->post(route('register'), [
            'name' => $studentName,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        // Assert
        $response->assertStatus(302);
    }
}
