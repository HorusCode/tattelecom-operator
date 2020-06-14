@extends('layouts.content')
@section('component')
    <h1 class="title">Заявления</h1>
    <div class="tile is-ancestor">
        <div class="tile is-parent">
            <div class="tile is-child box">
                <p class="title is-5">Заявления по месяцам</p>
                <month-statement-statistic></month-statement-statistic>
            </div>
        </div>
        <div class="tile is-parent">
            <div class="tile is-child box">
                <p class="title is-5">Состояния заявлений</p>
                <status-statement-statistic></status-statement-statistic>
            </div>
        </div>
    </div>
@endsection
