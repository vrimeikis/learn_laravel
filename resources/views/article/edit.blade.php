@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Article Edit
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form action="{{ route('article.update', [$article->id]) }}" method="post">

                            {{ method_field('put') }}

                            {{ csrf_field() }}

                            <div class="form-group">
                                <label for="title">{{ __('Title') }}:</label>
                                <input id="title" class="form-control" type="text" name="title"
                                       value="{{ old('title', $article->title) }}">
                                @if($errors->has('title'))
                                    <div class="alert-danger">{{ $errors->first('title') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="description">{{ __('Description') }}:</label>
                                <textarea id="description" class="form-control"
                                          name="description">{{ old('description', $article->description) }}</textarea>
                                @if($errors->has('description'))
                                    <div class="alert-danger">{{ $errors->first('description') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="author">{{ __('Author') }}:</label>
                                <input id="author" class="form-control" type="text" name="author"
                                       value="{{ old('author', $article->author) }}">
                                @if($errors->has('author'))
                                    <div class="alert-danger">{{ $errors->first('author') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="slug">{{ __('Slug') }}:</label>
                                <input id="slug" class="form-control" type="text" name="slug"
                                       value="{{ old('slug', $article->slug) }}">
                                @if($errors->has('slug'))
                                    <div class="alert-danger">{{ $errors->first('slug') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <input class="btn btn-success" type="submit" value="{{ __('Save') }}">
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
