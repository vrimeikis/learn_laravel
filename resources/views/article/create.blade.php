@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Article New
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                       <form action="{{ route('article.store') }}" method="post">

                           {{ csrf_field() }}

                           <div class="form-group">
                               <label for="title">{{ __('Title') }}:</label>
                               <input id="title" class="form-control" type="text" name="title" value="">
                           </div>

                           <div class="form-group">
                               <label for="description">{{ __('Description') }}:</label>
                               <textarea id="description" class="form-control" name="description"></textarea>
                           </div>

                           <div class="form-group">
                               <label for="author">{{ __('Author') }}:</label>
                               <input id="author" class="form-control" type="text" name="author" value="">
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
