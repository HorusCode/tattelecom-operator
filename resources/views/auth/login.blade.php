@extends('layouts.app')

@section('content')
    <div class="grid-login">
        <div class="col-bg" style="background-image: url('{{ asset('image/auth_bg.jpg') }}')"></div>
        <div class="col-form flex-col justify-content-center align-items-center">
            <h1>Логин</h1>
            <form class="form">
                <div class="input-line">
                    <label class="input-label" for="login">{{ __('auth.login') }}</label>
                    <input class="input" type="text" id="login" name="login">
                </div>
                <div class="input-line">
                    <label class="input-label" for="password">{{__('auth.password')}}</label>
                    <input class="input" type="text" id="password" name="password">
                </div>
                <div class="form--button-group">
                    <button type="button" class="btn btn-primary">Войти</button>
                </div>
            </form>
        </div>
    </div>
@endsection
