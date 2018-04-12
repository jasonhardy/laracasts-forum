<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_can_view_login_page()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    /** @test */
    public function a_user_can_login()
    {
        $user = create('App\Models\User');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret'
        ]);

        $response->assertStatus(302)
                 ->assertRedirect('/');

        $this->get('/login')
             ->assertRedirect('/');
    }

    /** @test */
    // public function testDoesNotLoginAnInvalidUser()
    // {
    //     $this->expectException('Illuminate\Auth\AuthenticationException');

    //     $user = create('App\Models\User');

    //     $response = $this->post('/login', [
    //         'email' => $user->email,
    //         'password' => 'invalid'
    //     ]);
    // }

    /** @test */
    public function a_user_can_logout()
    {
        $user = create('App\Models\User');

        $response = $this->actingAs($user)->post('/logout');

        $response->assertStatus(302)
                 ->assertRedirect('/');

    }
}