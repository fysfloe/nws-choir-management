@extends('layouts.app')

@section('content')

    <header class="page-header">
        <h2>{{ __('Create a new project') }}</h2>
    </header>

    {{ Form::model(new App\Project(), ['route' => 'project.store']) }}
        @include('project._form')
    {{ Form::close() }}

@endsection
