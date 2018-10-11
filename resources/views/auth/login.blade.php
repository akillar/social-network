@extends('layouts.app')

@section('content')

    <div class="container">

        <div class="row">

            <div class="col-md-6 mx-auto">

                @include('partials.validation-errors')

                <div class="card border-0 bg-light px-4 py-2">

                    <form action="{{ route('login') }}" method="POST">

                        @csrf

                        <div class="card-body">

                            <div class="form-group">
                                <label>Email:</label>
                                <input type="email" name="email" class="form-control border-0" placeholder="Tu email...">

                            </div>


                            <div class="form-group">
                                <label>Password:</label>
                                <input type="password" name="password" class="form-control border-0" placeholder="Contrasena">

                            </div>


                            <button dusk="login-btn" class="btn btn-primary btn-block">Login</button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

@endsection