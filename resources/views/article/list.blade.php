@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Articles List
                        <a class="btn btn-sm btn-primary" href="{{ route('article.create') }}">{{ __('New') }}</a>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <table class="table">
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Slug</th>
                                <th>Actions</th>
                            </tr>

                            @foreach($articles as $article)
                                <tr>
                                    <td>{{ $article->id }}</td>
                                    <td>{{ $article->title }}</td>
                                    <td>{{ $article->slug }}</td>
                                    <td>
                                        <a class="btn btn-sm btn-info" href="{{ route('article.show', [$article->id]) }}">{{ __('View') }}</a>
                                        <a class="btn btn-sm btn-success" href="{{ route('article.edit', [$article->id]) }}">{{ __('Edit') }}</a>
                                        <form action="{{ route('article.destroy', [$article->id]) }}" method="post">
                                            {{ csrf_field() }}
                                            {{ method_field('delete') }}
                                            <input class="btn btn-sm btn-danger" type="submit" value="Delete">
                                        </form>
                                    </td>
                                </tr>
                            @endforeach

                        </table>

                        {{ $articles->links() }}

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
