<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The login form can be displayed.
     *
     * @return void
     */
    public function testLoginFormDisplayed()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    /**
     * A valid user can be logged in.
     *
     * @return void
     */
    public function testLoginAValidUser()
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

    /**
     * An invalid user cannot be logged in.
     *
     * @return void
     */
    // public function testDoesNotLoginAnInvalidUser()
    // {
    //     $this->expectException('Illuminate\Auth\AuthenticationException');

    //     $user = create('App\Models\User');

    //     $response = $this->post('/login', [
    //         'email' => $user->email,
    //         'password' => 'invalid'
    //     ]);
    // }

    /**
     * A logged in user can be logged out.
     *
     * @return void
     */
    public function testLogoutAnAuthenticatedUser()
    {
        $user = create('App\Models\User');

        $response = $this->actingAs($user)->post('/logout');

        $response->assertStatus(302)
                 ->assertRedirect('/');

        //$this->dontSeeIsAuthenticated();
    }
}