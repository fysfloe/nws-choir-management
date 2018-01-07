@extends('layouts.filters')

@section('filtersInner')
    {{ Form::open(['route' => $route, 'method' => 'GET', 'class' => 'filter-form']) }}

    {{ Form::hidden('sort', app('request')->input('sort')) }}
    {{ Form::hidden('dir', app('request')->input('dir')) }}

    <div class="form-group">
        {{ Form::label('search', __('Search'), ['class' => 'control-label']) }}
        {{ Form::text('search', old('search'), ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {{ Form::label('voices[]', __('Voices'), ['class' => 'control-label']) }}

        <select name="voices[]" class="form-control" multiple="multiple">
            @foreach($voices as $key => $voice)
                <option value="{{ $key }}" @if (app('request')->input('voices') && in_array($key, app('request')->input('voices'))) {{ 'selected="selected"' }} @endif>{{ $voice }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group age-filter">
        {{ Form::label('age', __('Age'), ['class' => 'control-label']) }}
        {{ Form::number('age-from', old('age-from'), ['class' => 'form-control', 'min' => 10, 'max' => 110, 'placeholder' => __('Min')]) }}
        {{ Form::number('age-to', old('age-to'), ['class' => 'form-control', 'min' => 10, 'max' => 110, 'placeholder' => __('Max')]) }}
    </div>

    <div class="form-group">
        &nbsp;<br>
        {{ Form::button(__('Apply Filters'), ['type' => 'submit', 'class' => 'btn btn-primary btn-sm']) }}
    </div>

    {{ Form::close() }}
@endsection
