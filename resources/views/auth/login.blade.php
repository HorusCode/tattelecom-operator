@extends('layouts.app')
@section('title', 'Вход в систему')
@section('content')
    <div class="grid-login">
        <div class="col-bg" style="background-image: url('{{ asset('image/auth_bg.jpg') }}')"></div>
        <div class="col-form">
            <div class="logo">
                <img src="{{ asset('image/logo.png') }}" alt="Logo" width="120" height="120" class="logo--image">
            </div>
            <h1 class="title has-text-weight-normal text-center">{{ __('auth.title') }}</h1>
            <form class="form" method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field">
                    <label class="label">Логин</label>
                    <div class="control has-icons-left has-icons-right">
                        <input class="input @error('login') is-danger @enderror" type="text" placeholder="Логин" name="login">
                        <span class="icon is-small is-left">
                          <i class="mdi mdi-login-variant"></i>
                        </span>
                    </div>
                    @error('login')
                    <p class="help is-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label class="label">Пароль</label>
                    <div class="control has-icons-left has-icons-right">
                        <input class="input @error('password') is-danger @enderror" type="password" placeholder="Пароль" name="password">
                        <span class="icon is-small is-left">
                          <i class="mdi mdi-textbox-password"></i>
                        </span>
                        <span class="password-eye icon is-small is-right">
                          <i class="mdi mdi-eye-outline"></i>
                        </span>
                    </div>
                    @error('password')
                    <p class="help is-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form--button-group">
                    <button type="submit" class="button is-primary is-rounded">{{ __('auth.signin') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
