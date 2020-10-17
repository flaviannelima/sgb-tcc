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
            <div class="card border-secondary" id="login">
                <div class="card-header bg-primary text-white">{{ __('Login') }}</div>

                <div class="card-body text-secondary">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">

                            <div class="col-md-12">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-fw fa-envelope"></i></span>
                                    </div>
                                    <input id="email" type="email" placeholder="E-mail"
                                        class="form-control form-control-lg @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">


                            <div class="col-md-12">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-fw fa-lock"></i></span>
                                    </div>
                                    <input id="password" type="password" placeholder="Senha"
                                        class="form-control form-control-lg @error('password') is-invalid @enderror"
                                        name="password" required autocomplete="current-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                        {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success btn-lg btn-block font-weight-bold">
                                    {{ __('Login') }}
                                </button>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-12 text-center">
                                @if (Route::has('password.request'))
                                <a class="btn btn-link font-weight-bold text-center"
                                    href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-12 text-center">
                                <a class="btn btn-dark btn-lg btn-block font-weight-bold text-center"
                                    href="{{ route('register') }}">
                                    <span style="font-size:smaller;">Não possui uma conta? Crie uma agora!</span>
                                </a>
                            </div>
                        </div>
                        <div class="rounded-circle">
                            <p class="col-md-12 mt-4 text-center">OU</p>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-12 text-center">
                                <a href="{{ route('redirect') }}"
                                    class="btn btn-light btn-lg btn-block border-secondary">

                                    <img width="20px" alt="Entrar pela conta do Google"
                                        src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Google_%22G%22_Logo.svg/512px-Google_%22G%22_Logo.svg.png" />

                                    <span style="font-size:smaller;">Entrar com a conta do Google</span></a>
                            </div>



                        </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection