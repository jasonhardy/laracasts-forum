<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReadThreadsTest extends TestCase
{
    use RefreshDatabase;

    protected $thread;

    public function setUp()
    {
        parent::setUp();

        $this->thread = create('App\Models\Thread');
    }

    /** @test */
    public function a_user_can_view_all_threads()
    {
        $this->get('/threads')
            ->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_view_single_thread()
    {
        $this->get($this->thread->path())
            ->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_view_thread_replies()
    {
        $reply = create('App\Models\Reply', ['thread_id' => $this->thread->id]);

        $this->get($this->thread->path())
            ->assertSee($reply->body);
    }

    /** @test */
    public function a_user_can_filter_by_channel()
    {
        $channel = create('App\Models\Channel');

        $threadInChannel = create('App\Models\Thread', ['channel_id' => $channel->id]);
        $threadNotInChannel = create('App\Models\Thread');

        $this->get('/threads/' . $channel->slug)
             ->assertSee($threadInChannel->title)
             ->assertDontSee($threadNotInChannel->title);

    }

    /** @test */
    public function a_user_can_filter_by_username()
    {
        $this->signIn(create('App\Models\User', ['name' => 'JohnDoe']));

        $threadByJohn = create('App\Models\Thread', ['user_id' => auth()->id()]);
        $threadNotByJohn = create('App\Models\Thread');

        $this->get('threads?by=JohnDoe')
             ->assertSee($threadByJohn->title)
             ->assertDontSee($threadNotByJohn->title);
    }

    /** @test */
    public function a_user_can_filter_by_popularity()
    {
        $threadTwoReplies = create('App\Models\Thread');
        create('App\Models\Reply', ['thread_id' => $threadTwoReplies->id], 2);

        $threadThreeReplies = create('App\Models\Thread');
        create('App\Models\Reply', ['thread_id' => $threadThreeReplies->id], 3);

        $threadZeroReplies = $this->thread;

        $response = $this->get('threads?popular');
        $threadsFromResponse = $response->baseResponse->original->getData()['threads'];
        $this->assertEquals([3,2,0], $threadsFromResponse->pluck('replies_count')->toArray());
    }
}
