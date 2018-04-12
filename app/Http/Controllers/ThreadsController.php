<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Thread;
use App\Filters\ThreadFilters;
use Illuminate\Http\Request;

class ThreadsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['index','show']);
    }

    /**
     * Display a listing of all threads.
     * 
     * @param  Channel       $channel Optional channel passed in via slug
     * @param  ThreadFilters $filters Optional GET request parameters via url
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel, ThreadFilters $filters)
    {
        $threads = $this->getThreads($channel, $filters);

        return view('threads.index', compact('threads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $this->validate($request, [
            'channel_id' => 'required|exists:channels,id',
            'title' => 'required',
            'body'  => 'required'
        ]);

        $thread = Thread::create([
            'user_id' => auth()->id(),
            'channel_id'   => request('channel_id'),
            'title'   => request('title'),
            'body'    => request('body')
        ]);

        return redirect($thread->path());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show($channelSlug, Thread $thread)
    {
        $replies = $thread->replies()->paginate(10);
        return view('threads.show', compact('channel','thread','replies'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy($channelSlug, Thread $thread)
    {
        $this->authorize('update', $thread);

        $thread->replies()->delete();
        $thread->delete();
        return response([], 204);
    }

    protected function getThreads(Channel $channel, ThreadFilters $filters) 
    {
        $threads = Thread::filter($filters)->latest();

        if ($channel->exists) {
            $threads = $threads->whereChannelId($channel->id);
        } 
        
        return $threads->get();
    }
}
