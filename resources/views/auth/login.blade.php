@extends('layouts.app')

@section('content')
<div class="login-area">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="login">
                    <div class="login-top">
                        <div class="logo-left">
                            <img src="{{url('/images/bd govt.png')}}" alt="">
                        </div>
                        <div class="logo-text">
                            <div class="logo-text-bn">
                                অভ্যন্তরীণ সম্পদ বিভাগ
                            </div>
                            <div class="logo-text-en">
                                Internal Resource Division
                                <span style="color:#445640;font-size: large;">Store Management System</span>
                            </div>
                        </div>
                    </div>

                    <div class="logo-bottom login-form">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group row">
                                <div class="form-title"><i class="fa fa-lock"></i>  {{ __('messages.login') }}</div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12  text-center">
                                    <input id="email" type="username" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="{{ __('messages.email_address') }}" required autofocus>

                                    @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12  text-center">
                                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="{{ __('messages.password') }}" required>

                                    @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12 text-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('messages.remember_me') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('messages.login') }}
                                    </button>

                                    @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('messages.forgot_password') }}
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
