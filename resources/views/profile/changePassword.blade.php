@extends('layouts.app')

@section('content')

    <header class="page-header">
        <h2>{{ trans('Change Password') }}</h2>
    </header>

    {{ Form::open(['route' => 'profile.updatePassword', 'method' => 'POST']) }}

    <div class="form-group{{ $errors->has('old-password') ? ' has-error' : '' }}">
        {{ Form::label('old-password', trans('Old Password'), ['class' => 'control-label']) }}
        {{ Form::password('old-password', ['class' => 'form-control']) }}

        @if ($errors->has('old-password'))
            <span class="help-block text-danger">
                <strong>{{ $errors->first('old-password') }}</strong>
            </span>
        @endif
    </div>

    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
        {{ Form::label('password', trans('New Password'), ['class' => 'control-label']) }}
        {{ Form::password('password', ['class' => 'form-control']) }}

        @if ($errors->has('password'))
            <span class="help-block text-danger">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif
    </div>

    <div class="form-group">
        {{ Form::label('password_confirmation', trans('Confirm Password'), ['class' => 'control-label']) }}
        {{ Form::password('password_confirmation', ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {{ Form::button(trans('Save Password'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
    </div>

    {{ Form::close() }}

@endsection
