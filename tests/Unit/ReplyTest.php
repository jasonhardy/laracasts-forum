<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReplyTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function it_has_an_owner()
    {
        create('App\Models\Thread');
        $reply = create('App\Models\Reply');

        $this->assertInstanceOf('App\Models\User', $reply->owner);
    }
}
