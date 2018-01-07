@extends('layouts.modal')

@section('title')
    {{ __('Add dates') }}
@endsection()

@section('body')
    {{ Form::open(['route' => ['concert.saveDate', $concert], 'method' => 'POST']) }}

    <div class="form-group{{ $errors->has('dates') ? ' has-error' : '' }}">
        <input type="datetime-local" class="form-control date" name="dates[]">

        <a href="#" class="btn-link btn-sm add-date">
            <span class="oi oi-plus"></span>&nbsp;
            {{ __('Add another date') }}
        </a>
    </div><!-- .form-group -->

    {{ Form::close() }}
@endsection

@section('save')
    {{ __('Add date(s)') }}
@endsection

@section('close')
    {{ __('Cancel') }}
@endsection
