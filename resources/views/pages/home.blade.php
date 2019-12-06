@extends('layouts.app')
@section('title', $title)
@section('content')
    <div class="app-container">
        @include('includes.header')
        <div class="app-container__content tile is-ancestor">
            <div class="tile is-vertical is-12">
                <div class="tile">
                    <div class="tile is-parent is-vertical">
                        <article class="tile is-child">

                            @switch(Auth::user()->employee->name)
                                @case('client_operator')
                           <new-statement-table data="{{ $data }}"></new-statement-table>
                                @break
                                @case('service')
                                <new-work-table data="{{ $data }}"></new-work-table>
                                @break
                                @endswitch
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
