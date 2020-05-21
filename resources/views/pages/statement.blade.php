@extends('layouts.content')
@section('component')
    <statements
        data="{{ $data }}"
        btn-type="{{ $btnType ?? '' }}"
        :show-btn="{{ $showBtn ?? 'false' }}"
        role="{{ Auth()->user()->employee->name }}"
    ></statements>
@endsection
