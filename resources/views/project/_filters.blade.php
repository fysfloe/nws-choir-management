@extends('layouts.filters')

@section('filtersInner')
    {{ Form::open(['route' => 'projects', 'method' => 'GET', 'class' => 'filter-form']) }}

    {{ Form::hidden('sort', app('request')->input('sort')) }}
    {{ Form::hidden('dir', app('request')->input('dir')) }}

    <div class="form-group">
        {{ Form::label('search', __('Search'), ['class' => 'control-label']) }}
        {{ Form::text('search', old('search'), ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        &nbsp;<br>
        {{ Form::button(__('Apply Filters'), ['type' => 'submit', 'class' => 'btn btn-primary btn-sm']) }}
    </div>

    {{ Form::close() }}
@endsection
