<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipateInForumTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_may_not_reply()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->post('/threads/channel_slug/1/replies', []);
    }

    /** @test */
    public function an_authenticated_user_may_participate_in_forum()
    {
        $this->be(create('App\Models\User'));
        $thread = create('App\Models\Thread');
        $reply = make('App\Models\Reply');

        $this->post($thread->path() . '/replies', $reply->toArray());

        $this->get($thread->path())
             ->assertSee($reply->body);
    }

    /** @test */
    public function a_reply_requires_a_body()
    {
        $this->withExceptionHandling()->signIn();
        $thread = create('App\Models\Thread');
        $reply = make('App\Models\Reply', ['body' => null]);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }
}
