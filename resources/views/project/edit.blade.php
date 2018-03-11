@extends('layouts.app')

@section('content')

    <header class="page-header">
        <h2><span class="light">{{ __('Edit') }}:</span> {{ $project->title }}</h2>
    </header>

    {{ Form::model($project, ['route' => ['project.update', $project->id], 'method' => 'PUT']) }}
        @include('project._form')
    {{ Form::close() }}

@endsection
