@extends('layouts.app')

@section('content')

    <header class="page-header">
        <h2>Create a new user</h2>
    </header>

    {{ Form::model(new App\User(), ['route' => 'users.store']) }}
        @include('user._form')
    {{ Form::close() }}

@endsection
