@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">News Reader Fail</div>

                    <div class="card-body">
                        @if(!config('settings.rss_url'))
                            RSS feed url has not been specified, please specify url
                        @elseif(!preg_match(config('app.url_parser'), config('settings.rss_url')))
                            RSS url has not been entered correctly
                        @else
                            RSS reader could not find any suitable reader, please enable one of following:
                                <ol>
                                    <li>php library <strong>Curl</strong></li>
                                    <li>php ini setting: <strong>allow_url_fopen</strong></li>
                                    <li>php <strong>fsockopen</strong> function</li>
                                </ol>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection