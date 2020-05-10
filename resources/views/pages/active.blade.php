@extends('layouts.app')
@section('title', $title)
@section('content')
    <div class="app-container">
        <div class="app-container__content tile is-ancestor">
            <div class="tile is-vertical is-12">
                <div class="tile">
                    <div class="tile is-parent is-vertical">
                        <article class="tile is-child">
                            @switch(Auth::user()->employee->name)
                                @case('client_operator')
                                <active-statement-table data="{{ $data }}"></active-statement-table>
                                @break
                                @case('service')
                                <active-work-table data="{{ $data }}"></active-work-table>
                                @break
                            @endswitch
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
