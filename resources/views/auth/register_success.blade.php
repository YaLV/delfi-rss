@extends('layouts.app')

@section('content')
    @if(session('emailAddress'))
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            {{ __('Registration Successful') }}
                        </div>

                        <div class="card-body">
                            Registration was successful, we sent confirmation email to {{ session('emailAddress') }}. Please
                            verify your email address before proceeding.

                            If you did not received confirmation email, please check spam folder
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            {{ __('Registration Failed') }}
                        </div>

                        <div class="card-body">
                            Registration Failed, due to some fail in system
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection