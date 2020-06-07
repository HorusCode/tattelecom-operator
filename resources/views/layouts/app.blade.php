<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->id() }}">

    <title> @yield('title') </title>


    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app" class="{{ (Route::currentRouteName() != 'login-form') ? 'grid-main' : '' }}">
    @if(Route::currentRouteName() != 'login-form')
        @include('includes.sidebar')
        @include('includes.header')
    @endif
    <main class="w-content">
        @yield('content')
    </main>
</div>
<script src="{{ mix('js/manifest.js') }}"></script>
<script src="{{ mix('js/vendor.js') }}"></script>
<script src="{{ mix('js/app.js') }}"></script>
@stack('scripts')
@if(Route::currentRouteName() == 'inactive')
    <script type="text/javascript">
      localStorage.setItem('token', '{{ Auth::user()->api_token }}');
    </script>
@endif
</body>
</html>
