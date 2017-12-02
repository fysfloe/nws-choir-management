@extends('layouts.app')

@section('content')
    <header class="page-header">
        <h2>Register</h2>
    </header>

    <form method="POST" action="{{ route('register') }}" novalidate>
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
            <label for="firstname" class="control-label">First Name</label>


            <input id="firstname" type="text" class="form-control" name="firstname" value="{{ old('firstname') }}" required autofocus>

            @if ($errors->has('firstname'))
                <span class="help-block">
                    <strong>{{ $errors->first('firstname') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group{{ $errors->has('surname') ? ' has-error' : '' }}">
            <label for="surname" class="control-label">{{ trans('Surname') }}</label>

            <input id="surname" type="text" class="form-control" name="surname" value="{{ old('surname') }}" required autofocus>

            @if ($errors->has('surname'))
                <span class="help-block">
                    <strong>{{ $errors->first('surname') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="email" class="control-label">{{ trans('E-Mail Address') }}</label>

            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
            <label for="gender" class="control-label">{{ trans('Gender') }}</label>

            <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="gender" value="f" @if (old('gender') === 'f') {{ 'checked="checked"' }} @endif required>
                    {{ trans('female') }}
                </label>
            </div>
            <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="gender" value="m" @if (old('gender') === 'f') {{ 'checked="checked"' }} @endif required>
                    {{ trans('male') }}
                    </label>
            </div>

            @if ($errors->has('gender'))
                <span class="help-block">
                    <strong>{{ $errors->first('gender') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password" class="control-label">Password</label>
            <input id="password" type="password" class="form-control" name="password" required>

            @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            <label for="password-confirm" class="control-label">Confirm Password</label>
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">
                Register
            </button>
        </div>
    </form>
@endsection
