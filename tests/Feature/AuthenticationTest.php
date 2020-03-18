<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Judite\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthenticationTest extends TestCase
{
    use DatabaseTransactions;

    /** @test **/
    public function user_email_login_check_is_case_insensitve()
    {
        $user = factory(User::class)->create([
            'email' => 'a7000@'.config('app.mail_domain'),
            'password' => 'password',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'A7000@'.config('app.mail_domain'),
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
    }
}
