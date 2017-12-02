@extends('layouts.app')

@section('content')

    <header class="page-header">
        <h2>Create a new concert</h2>
    </header>

    {{ Form::model(new App\Concert(), ['route' => 'concert.store']) }}
        @include('concert._form')
    {{ Form::close() }}

@endsection
