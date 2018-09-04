@extends('layouts.front')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>{{ $article->title }}</h3>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 text-center text-sm-left">
                                @if ($article->cover)
                                    <img src="{{ Storage::url($article->cover) }}">
                                @endif
                            </div>
                            <div class="col-md-6">
                                <p>{{ __('Author:') }} <em>{{ $article->author->first_name }} {{ $article->author->last_name }}</em></p>
                                <p>
                                    {{ __('Categories:') }}
                                    @foreach($article->categories as $category)
                                        <em>
                                            <a href="{{ route('front.category.articles', $category->slug) }}">
                                                {{ $category->title }}
                                            </a>
                                        </em>
                                    @endforeach
                                </p>
                                <p>{{ __('Created at:') }} <em>{{ $article->created_at->diffForHumans() }}</em></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                {{ $article->description }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection