@extends('layouts.app')
{{--@section('title', $title)--}}
@section('content')
    <div class="app-container">
        <article>
            @yield('component')
        </article>
    </div>
@endsection

