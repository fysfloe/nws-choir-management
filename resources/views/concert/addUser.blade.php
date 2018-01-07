@extends('layouts.modal')

@section('title')
    {{ __('Add participants') }}
@endsection()

@section('body')
    @if (count($users) > 0)
        {{ Form::open(['route' => ['concert.addUser', $concert], 'method' => 'POST']) }}

        <div class="form-group{{ $errors->has('users') ? ' has-error' : '' }}">
            {{ Form::label('users[]', __('Select Users'), ['class' => 'control-label']) }}
            {{ Form::select('users[]', $users, null, ['class' => 'form-control', 'multiple' => 'multiple']) }}
        </div><!-- .form-group -->

        {{ Form::close() }}
    @else
        <small class="help-text text-muted">{{ __('All registered users already attend the concert.') }}</small>
    @endif
@endsection

@section('save')
    {{ __('Add participants') }}
@endsection

@section('close')
    {{ __('Cancel') }}
@endsection
