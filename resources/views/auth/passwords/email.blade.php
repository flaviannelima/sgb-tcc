@extends('layouts.app')

@section('content')
<style>
    .fullscreen_bg {
        position: fixed;
        top: 9%;
        right: 0;
        bottom: 0;
        left: 0;
        background-size: cover;
        background-position: 50% 50%;
        background-image: url('https://adrianajarva.com/wp-content/uploads/2018/12/thumb-1920-330109.jpg');
        background-repeat: repeat;
    }

    nav {
        z-index: 100000;
    }
</style>
<div class="container">
    <div id="fullscreen_bg" class="fullscreen_bg" />
    <div class="row justify-content-center align-items-center h-100">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group row">

                            <div class="col-md-12">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-fw fa-envelope"></i></span>
                                    </div>
                                    <input id="email" type="email"
                                        class="form-control form-control-lg @error('email') is-invalid @enderror"
                                        placeholder="E-mail" name="email" value="{{ old('email') }}" required
                                        autocomplete="email" autofocus>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-success btn-lg btn-block">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection