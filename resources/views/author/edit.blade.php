@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Authror Edit
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form action="{{ route('author.update', [$author->id]) }}" method="post">

                            {{ method_field('put') }}

                            {{ csrf_field() }}

                            <div class="form-group">
                                <label for="first_name">{{ __('First name') }}:</label>
                                <input id="first_name" class="form-control" type="text" name="first_name"
                                       value="{{ old('first_name', $author->first_name) }}">
                                @if($errors->has('first_name'))
                                    <div class="alert-danger">{{ $errors->first('first_name') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="last_name">{{ __('Last name') }}:</label>
                                <input id="last_name" class="form-control" type="text" name="last_name"
                                       value="{{ old('last_name', $author->last_name) }}">
                                @if($errors->has('last_name'))
                                    <div class="alert-danger">{{ $errors->first('last_name') }}</div>
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
