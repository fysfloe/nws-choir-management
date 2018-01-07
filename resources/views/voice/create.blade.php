@extends('layouts.app')

@section('content')

    <header class="page-header">
        <h2>{{ __('Create a new voice') }}</h2>
    </header>

    {{ Form::model(new App\Voice(), ['route' => 'voices.store']) }}
        @include('voice._form')
    {{ Form::close() }}

@endsection
