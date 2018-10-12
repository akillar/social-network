@extends('layouts.app')

@section('content')

    <div class="container">

        @foreach($friendshipRequests as $friendshipRequest)

            <accept-friendship-btn :sender="{{ $friendshipRequest->sender }}"
                                   friendship-status="{{ $friendshipRequest->status }}"
                                   dusk="accept-friendship"
            ></accept-friendship-btn>

        @endforeach

    </div>

@endsection