@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Authors
                        <a class="btn btn-sm btn-primary" href="{{ route('author.create') }}">{{ __('New') }}</a>
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
                                <th>First name</th>
                                <th>Last name</th>
                                <th>Location type</th>
                                <th>Actions</th>
                            </tr>

                            @foreach($authors as $author)
                                <tr>
                                    <td>{{ $author->id }}</td>
                                    <td>{{ $author->first_name }}</td>
                                    <td>{{ $author->last_name }}</td>
                                    <td>{{ \App\Enum\AuthorLocationTypeEnum::from($author->location_type)->getName() }}</td>
                                    <td>
                                        <a class="btn btn-sm btn-success" href="{{ route('author.edit', [$author->id]) }}">{{ __('Edit') }}</a>
                                    </td>
                                </tr>
                            @endforeach

                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
