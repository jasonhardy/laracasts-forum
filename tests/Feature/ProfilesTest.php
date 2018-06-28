<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfilesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_has_profile ()
    {
        $user = create('App\Models\User');

        $this->get('/profiles/'.$user->name)
             ->assertSee($user->name);
    }

    /** @test */
    public function profile_has_user_threads ()
    {
        $this->signIn();

        $thread = create('App\Models\Thread', ['user_id' => auth()->id()]);

        $this->get('/profiles/'.auth()->user()->name)
             ->assertSee($thread->title)
             ->assertSee($thread->body);
    }
}