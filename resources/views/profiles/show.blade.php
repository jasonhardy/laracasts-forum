@extends('layouts.app')

@section('content')
<div class="container">
    <div class="page-header">
        <h1>{{ $profileUser->name }}</h1>
    </div>

    @foreach ($threads as $thread)
    <br>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                
                <div class="card-header">
                    {{ $thread->title }}
                </div>

                <div class="card-body">
                    {{ $thread->body }}
                    <br><br>
                    @can ('update', $thread)
                    <form method="POST" action="{{ $thread->path() }}">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button type="submit" class="btn btn-sm btn-primary">Delete This Thread</button>
                    </form>
                    @endcan
                </div>

            </div>
        </div>
    </div>
    @endforeach
    <br>
    {{ $threads->links() }}

</div>
@endsection
