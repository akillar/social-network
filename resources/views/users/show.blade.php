@extends('layouts.app')

@section('content')

    <div class="container">

        <div class="row">

            <div class="col-md-3">

                <div class="card border-0 bg-light shadow-sm">

                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="card-img-top">

                    <div class="card-body">

                        @if(auth()->id() === $user->id)

                            <h5 class="card-title">

                                {{ $user->name }} <small class="text-secondary">This is me</small>

                            </h5>

                        @else

                            <h5 class="card-title">

                                {{ $user->name }}

                            </h5>

                            <friendship-btn friendship-status="{{ $friendshipStatus }}"
                                            :recipient="{{ $user }}"
                                            dusk="request-friendship"
                            ></friendship-btn>
                        @endif

                    </div>

                </div>

            </div>

            <div class="col-md-9">

                <div class="card border-0 bg-light shadow-sm">

                    <div class="card-body">

                        Contenido

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection