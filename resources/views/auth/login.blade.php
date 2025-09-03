@extends('layouts.app')
@section('content')
@php
  $lang =  \Session::get('lang');
  if(empty($lang)){
    $lang = "en";
  }
  app()->setLocale($lang);
  @endphp
  <style>
 

.auth.auth-img-bg .auth-form-transparent {
    width: 55%;
    margin: auto;
    margin-top: 132px;
}
  </style>
<div class="container-fluid page-body-wrapper full-page-wrapper">
    <div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
    <div class="row flex-grow">
        <div class="col-lg-6 d-flex align-items-center justify-content-center login-forgot-commontext">
        <div class="auth-form-transparent text-left p-3">
            <!-- <a class="navbar-brand" href="{{ url('/') }}"><img src="{{ asset('images/logo.png') }}" alt="logo"/></a> -->
            
            <div class="brand-logo">
                @if (session('status'))
                    <p class="alert alert-success">{{ session('status') }}</p>
                @endif
                @if (session('failure'))
                    <p class="alert alert-danger">{{ session('failure') }}</p>
                @endif
            </div>
            <h6 class="font-weight-light"> Welcome! Happy to see you! </h6>
            <form method="POST" action="{{ route('login') }}" class="pt-3">
                @csrf

            <div class="form-group">
                <label for="email">{{ __('E-Mail Address') }} </label>
                <div class="input-group">
                <div class="input-group-prepend bg-transparent">
                    <span class="input-group-text bg-transparent border-right-0">
                    <i class="la la-user mdi-account-outline text-primary"></i>
                    </span>
                </div>
                <input id="email" type="email" class="form-control-lg border-left-0 form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
                </div>
            </div>
            <div class="form-group" id="show_hide_password1">
                <label for="password">{{ __('Password') }}</label>
                <div class="input-group">
                <div class="input-group-prepend bg-transparent">
                   <span class="input-group-text bg-transparent border-right-0">
                    <a href=""><i class="la la-lock" aria-hidden="true"></i></a>
                    </span>
                </div>
                <input id="password" type="password" class="form-control-lg border-left-0 form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required autocomplete="current-password">
                @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
                </div>
            </div>
            <div class="my-2 d-flex justify-content-between align-items-center">
                <div class="form-check">
                <label class="form-check-label text-muted">
                    <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    {{ __('Remember Me') }}
                </label>
                </div>
               
            </div>
            <div class="my-3">
                <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">  {{ __('Login') }} </button>
            </div>

            <!-- <div class="text-center mt-4 font-weight-light">
                Don't have an account? <a href="{{ route('register') }}" class="text-primary">Create</a>
            </div> -->
            </form>
        </div>
        </div>
        <div class="col-lg-6 d-flex flex-row login-half-bg login-forgot-commontext  " style="padding:0px">
          <img src="{{URL::asset('/image/5.jpeg')}}"/>    
        <!-- <p class="text-white font-weight-medium text-center flex-grow align-self-end"> {{ __('a.Copyrightaccount-anrest') }} </p> -->
        </div>
    </div>
    </div>
    <!-- content-wrapper ends -->
</div>
<!-- page-body-wrapper ends -->

@endsection
