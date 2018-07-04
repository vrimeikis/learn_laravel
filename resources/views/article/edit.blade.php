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
                               <input id="title" class="form-control" type="text" name="title" value="{{ $article->title }}">
                           </div>

                           <div class="form-group">
                               <label for="description">{{ __('Description') }}:</label>
                               <textarea id="description" class="form-control" name="description">{{ $article->description }}</textarea>
                           </div>

                           <div class="form-group">
                               <label for="author">{{ __('Author') }}:</label>
                               <input id="author" class="form-control" type="text" name="author" value="{{ $article->author }}">
                           </div>

                           <div class="form-group">
                               <label for="slug">{{ __('Slug') }}:</label>
                               <input id="slug" class="form-control" type="text" name="slug" value="{{ $article->slug }}">
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
