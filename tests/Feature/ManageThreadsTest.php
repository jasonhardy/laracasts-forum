<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_may_not_create_threads()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $thread = make('App\Models\Thread');
        $this->post('/threads', $thread->toArray());
    }

    /** @test */
    public function guests_may_not_view_create_page()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->get('/threads/create');
    }

    /** @test */
    public function an_authenticated_user_can_create_threads()
    {
        $this->signIn();

        $this->get('/threads/create')
             ->assertStatus(200);
        
        $thread = make('App\Models\Thread');
        $response = $this->post('/threads', $thread->toArray());

        $this->get($response->headers->get("location"))
            ->assertSee($thread->title);
    }

    /** @test */
    public function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_thread_requires_a_valid_channel()
    {
        factory('App\Models\Channel', 2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 9])
            ->assertSessionHasErrors('channel_id');
    }

    /** @test */
    public function unauthorized_users_may_not_delete_threads()
    {
        $this->withExceptionHandling();

        $thread = create('App\Models\Thread');

        $this->delete($thread->path())
             ->assertRedirect('/login');

        $this->signIn();
        $this->delete($thread->path())
             ->assertStatus(403);
    }

    /** @test */
    public function authorized_users_may_delete_threads()
    {
        $this->signIn();

        $thread = create('App\Models\Thread', ['user_id' => auth()->id()]);
        $reply = create('App\Models\Reply', ['thread_id' => $thread->id]);

        $this->json('DELETE', $thread->path())
             ->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }

    /** @test */
    public function authorized_admins_may_delete_threads()
    {
        $admin = create('App\Models\User', ['name' => 'Jason']);
        $this->signIn($admin);

        $user = create('App\Models\User');

        $thread = create('App\Models\Thread', ['user_id' => $user->id]);
        $reply = create('App\Models\Reply', ['thread_id' => $thread->id]);

        $this->json('DELETE', $thread->path())
             ->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }

    public function publishThread ($overrides = [])
    {
        $this->withExceptionHandling()->signIn();
        $thread = make('App\Models\Thread', $overrides);
        return $this->post('/threads', $thread->toArray());
    }
}
