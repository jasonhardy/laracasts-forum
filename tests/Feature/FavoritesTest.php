<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FavoritesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_can_not_favorite_anything()
    {
        $this->withExceptionHandling()
             ->post('replies/1/favorites')
             ->assertRedirect('login');
    }

    /** @test */
    public function an_authenticated_user_can_favorite_a_reply()
    {
        $this->signIn();

        $thread = create('App\Models\Thread');
        $reply = create('App\Models\Reply');

        $favorite = $this->post('replies/' . $reply->id . '/favorites');

        $this->assertCount(1, $reply->favorites);
    }

    /** @test */
    public function an_authenticated_user_can_only_favorite_a_reply_once()
    {
        $this->signIn();

        create('App\Models\Thread');
        $reply = create('App\Models\Reply');

        try {
            $this->post('replies/' . $reply->id . '/favorites');
            $this->post('replies/' . $reply->id . '/favorites');
        } catch (\Exception $e) {
            $this->fail('Cannot insert multiple reply favorites.');
        }

        $this->assertCount(1, $reply->favorites);
    }
}
