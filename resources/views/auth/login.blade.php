@extends('layouts.app')

@section('content')
    <div class="grid-login">
        <div class="col-bg" style="background-image: url('{{ asset('image/auth_bg.jpg') }}')"></div>
        <div class="col-form flex-col justify-content-center align-items-center">
            <div class="logo">
                <img src="{{ asset('image/logo.png') }}" alt="Logo" width="120" height="120" class="logo--image">
            </div>
            <h1>{{ __('auth.title') }}</h1>
            <form class="form" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="input-line">
                    <label class="input-label" for="login">{{ __('auth.login') }}</label>
                    <div class="input-group">
                        <input class="input @error('login') invalid @enderror" type="text" id="login" name="login">
                        <span class="mdi mdi-login-variant icon-left"></span>
                    </div>
                    @error('login')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror

                </div>
                <div class="input-line">
                    <label class="input-label" for="password">{{__('auth.password')}}</label>
                    <div class="input-group">
                        <input class="input @error('password') invalid @enderror" type="password" id="password" name="password">
                        <span class="mdi mdi-textbox-password icon-left"></span>
                        <span class="mdi mdi-eye-outline icon-right text-muted"></span>
                    </div>
                    @error('password')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form--button-group">
                    <button type="submit" class="btn btn-primary">{{ __('auth.signin') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
