@extends('layouts.front')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row">
                    @foreach($articles as $article)
                        <div class="col-md-6">
                            @if ($article->cover)
                                <img class="pull-left pr-2" src="{{ Storage::url($article->cover) }}" width="150">
                            @endif
                            <h5>
                                <a href="{{ route('front.article', $article->slug) }}">
                                    {{ $article->title }}
                                </a>
                            </h5>
                            <p>{{ __('Created by:') }} {{ $article->author->first_name }} {{ $article->author->last_name }}</p>
                            <p>{{ __('Posted at:') }} {{$article->created_at}}</p>
                            <p>{{ str_limit($article->description) }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
