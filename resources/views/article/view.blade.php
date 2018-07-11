@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Article View: {{ $article->title }}
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <table class="table">
                            <tr>
                                <td>{{ __('Title') }}:</td>
                                <td>{{ $article->title }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('Description') }}:</td>
                                <td>{{ $article->description }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('Author') }}:</td>
                                <td>{{ $article->author->first_name }} {{ $article->author->last_name }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('Slug') }}:</td>
                                <td>{{ $article->slug }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('Created') }}:</td>
                                <td>{{ $article->created_at }}</td>
                            </tr>
                        </table>

                        <a class="btn btn-secondary" href="javascript:history.back();">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
