@extends('layouts.modal')

@section('title')
    {{ trans('Add dates') }}
@endsection()

@section('body')
    {{ Form::open(['route' => 'concert.saveDate', 'method' => 'POST']) }}

    {{ Form::hidden('concertId', $concertId) }}

    <div class="form-group{{ $errors->has('dates') ? ' has-error' : '' }}">
        <input type="datetime-local" class="form-control date" name="dates[]">

        <a href="#" class="btn-link btn-sm add-date">
            <span class="oi oi-plus"></span>&nbsp;
            {{ trans('Add another date') }}
        </a>
    </div><!-- .form-group -->

    {{ Form::close() }}
@endsection

@section('save')
    {{ trans('Add date(s)') }}
@endsection

@section('close')
    {{ trans('Cancel') }}
@endsection
