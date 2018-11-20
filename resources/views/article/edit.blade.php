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

                        <form action="{{ route('article.update', [$article->id]) }}" method="post"
                              enctype="multipart/form-data">

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
                                <label for="cover">{{ __('Cover') }}</label>
                                @if ($article->cover)
                                    <br>
                                    <img width="200" src="{{ Storage::url($article->cover) }}">
                                @endif
                                <input id="cover" class="form-control" type="file" name="cover" accept=".jpg, .jpeg, .png">
                                @if($errors->has('cover'))
                                    <div class="alert-danger">{{ $errors->first('cover') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="description">{{ __('Description') }}:</label>
                                <textarea id="description" class="form-control" name="description">{{ old('description', $article->description) }}</textarea>
                                @if($errors->has('description'))
                                    <div class="alert-danger">{{ $errors->first('description') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="article_type">{{ __('Article type') }}</label>
                                <select class="form-control" id="article_type" name="article_name">
                                    @foreach($articleTypes as $articleType)
                                        <option value="{{ $articleType['id'] }}">{{ $articleType['name'] }} | {{ $articleType['description'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="author_id">{{ __('Author') }}:</label>
                                <select id="author_id" class="form-control" name="author_id">
                                    <option value=""> ---</option>
                                    @foreach($authors as $author)
                                        <option value="{{ $author->id }}" {{ ($author->id == old('author_id', $article->author_id) ? 'selected' : '') }}>{{ $author->first_name }} {{ $author->last_name }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('author_id'))
                                    <div class="alert-danger">{{ $errors->first('author_id') }}</div>
                                @endif
                            </div>

                            <div class="form_group">
                                <label>{{ __('Categories') }}</label>
                                <br>
                                @foreach($categories as $category)
                                    <label for="category_{{ $category->id }}">
                                        <input id="category_{{ $category->id }}" type="checkbox" name="category[]"
                                               value="{{ $category->id }}"
                                                {{ (in_array($category->id, old('category', $article->categories->pluck('id')->toArray())) ? 'checked' : '') }}
                                        > {{ $category->title }}
                                    </label>
                                    <br>
                                @endforeach
                                @if($errors->has('category'))
                                    <div class="alert-danger">{{ $errors->first('category') }}</div>
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
