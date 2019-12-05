@extends('layouts.app')

@section('content')
    <div class="app-container">
        @include('includes.header')
        <div class="app-container__content tile is-ancestor">
            <div class="tile is-vertical is-12">
                <div class="tile">
                    <div class="tile is-parent is-vertical">
                        <article class="tile is-child">
                           <new-statement-table data="{{ $data }}"></new-statement-table>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
