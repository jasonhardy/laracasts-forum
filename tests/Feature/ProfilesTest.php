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
        $user = create('App\Models\User');

        $thread = create('App\Models\Thread', ['user_id' => $user->id]);

        $this->get('/profiles/'.$user->name)
             ->assertSee($thread->title)
             ->assertSee($thread->body);
    }
}