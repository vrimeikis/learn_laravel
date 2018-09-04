@extends('layouts.front')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>{{ $category->title }}</h3>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            @foreach($articles as $item)
                                @include('front.partials._article', ['article' => $item])
                            @endforeach
                        </div>
                    </div>
                    <div class="card-footer">
                        {{ $articles->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection