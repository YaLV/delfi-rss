@extends('layouts.app')

@section('content')
    @if($result)
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('Email Address Verified') }}</div>

                        <div class="card-body">
                            {!! __('Your email address has been verified, you can now <a href=":loginroute">login</a>', ['loginroute' => route('login')])  !!}
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
                        <div class="card-header">{{ __('Email Verification Failed') }}</div>

                        <div class="card-body">
                            {{ __('The validation url is not valid') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endif
@endsection