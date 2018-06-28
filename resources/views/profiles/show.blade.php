@extends('layouts.app')

@section('content')
<div class="container">
    <div class="page-header">
        <h1>{{ $profileUser->name }}</h1>
    </div>

    @foreach ($activities as $date => $activity)
    <br>
    <div class="row">
        <div class="col-md-8">
            <h3>{{ $date }}</h3>
            @foreach ($activity as $record)
                @include ("profiles.activities.{$record->type}", ['activity' => $record])
            @endforeach
        </div>
    </div>
    @endforeach

</div>
@endsection
