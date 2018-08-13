@extends('layouts.front')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Contact us') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-5">
                                <form action="" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label for="full_name">{{ __('Full name') }}</label>
                                        <input class="form-control" id="full_name" name="full_name" type="text"
                                               value="{{ old('full_name') }}">
                                        @if($errors->has('full_name'))
                                            <div class="alert-danger">{{ $errors->first('full_name') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="email">{{ __('Email') }}</label>
                                        <input class="form-control" id="email" name="email" type="email"
                                               value="{{ old('email') }}">
                                        @if($errors->has('email'))
                                            <div class="alert-danger">{{ $errors->first('email') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="message">{{ __('Message') }}</label>
                                        <textarea rows="5" id="message" name="message"
                                                  class="form-control">{{ old('message') }}</textarea>
                                        @if($errors->has('message'))
                                            <div class="alert-danger">{{ $errors->first('message') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <input class="btn btn-success" name="submit_message" type="submit"
                                               value="{{ __('Send') }}">
                                    </div>

                                </form>
                            </div>
                            <div class="col-md-7">
                                Laisves al. 51a, Kaunas<br>
                                HappSpace patalpos
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection