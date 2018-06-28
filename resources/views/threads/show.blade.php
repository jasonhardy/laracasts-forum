@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                
                <div class="card-header">
                    {{ $thread->title }}
                    by <a href="/profiles/{{$thread->creator->name}}">{{$thread->creator->name}}</a>
                </div>

                <div class="card-body">
                    {{ $thread->body }}
                </div>

            </div>

            @foreach ($replies as $reply)
                @include('threads.reply')
            @endforeach
            <br>
            {{ $replies->links() }}

            @if (auth()->check())
            <br>
            <form method="POST" action="{{ $thread->path() }}/replies">
                {{ csrf_field() }}
                <div class="form-group">
                    <textarea name="body" id="body" class="form-control" placeholder="Tell me like it is ..." rows="5"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Reply</button>
            </form>
            @endif

        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <p>
                        Channel: <a href="/threads/{{ $thread->channel->slug }}">{{ $thread->channel->name }}</a>
                    </p>
                    <p>
                        This thread was published {{ $thread->created_at->diffForHumans() }} by
                        <a href="/profiles/{{ $thread->creator->name }}">{{ $thread->creator->name }}</a>, 
                        and has {{ $thread->replies_count }} {{ str_plural('comment', $thread->replies_count) }}.   
                    </p>

                    @can ('update', $thread)
                        <form method="POST" action="{{ $thread->path() }}">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <button type="submit" class="btn btn-primary">Delete Thread</button>
                        </form>
                    @endcan

                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
