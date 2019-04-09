@extends('layouts.app')

@section('content')
    <div class="container">
        @foreach($items as $item)
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-9">{{ (string)$item->title }}</div>
                                <div class="col-md-3">{{ \Carbon\Carbon::createFromTimeString($item->pubDate)->format('d.m.Y H:i') }}</div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($image = getImage($item))
                                <img src="{{ $image }}"/>
                            @endif
                            {!! (string)$item->description  !!}
                                <span class="readmore"><a href="{{ $item->link }}" target="_blank">Read More &raquo;</a></span>
                        </div>
                    </div>
                </div>
            </div>
            @if($loop->index==20)
                @break;
            @endif
        @endforeach
    </div>
@endsection

@push('css')
    <link href="{{ asset('css/custom.css') }}" type="text/css" rel="stylesheet" />
@endpush