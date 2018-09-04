@extends('layouts.front')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row">
                    @foreach($articles as $art)
                        @include('front.partials._article', ['article' => $art])
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
