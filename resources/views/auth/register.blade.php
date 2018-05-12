@extends('layouts.app')

@section('content')
    <header class="page-header">
        <h2>{{ __('Register') }}</h2>
    </header>

    <form method="POST" action="{{ route('register') }}" novalidate>
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
            <label for="firstname" class="control-label">{{ __('First Name') }}</label>


            <input id="firstname" type="text" class="form-control" name="firstname" value="{{ old('firstname') }}" required autofocus>

            @if ($errors->has('firstname'))
                <span class="help-block text-danger">
                    <strong>{{ $errors->first('firstname') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group{{ $errors->has('surname') ? ' has-error' : '' }}">
            <label for="surname" class="control-label">{{ __('Surname') }}</label>

            <input id="surname" type="text" class="form-control" name="surname" value="{{ old('surname') }}" required autofocus>

            @if ($errors->has('surname'))
                <span class="help-block text-danger">
                    <strong>{{ $errors->first('surname') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
            <label for="username" class="control-label">{{ __('Username') }}</label>

            <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required autofocus>

            @if ($errors->has('username'))
                <span class="help-block text-danger">
                    <strong>{{ $errors->first('username') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="email" class="control-label">{{ __('E-Mail Address') }}</label>

            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

            @if ($errors->has('email'))
                <span class="help-block text-danger">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
            <label for="gender" class="control-label">{{ __('Gender') }}</label>

            <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="gender" value="f" @if (old('gender') === 'f') {{ 'checked="checked"' }} @endif required>
                    {{ __('female') }}
                </label>
            </div>
            <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="gender" value="m" @if (old('gender') === 'f') {{ 'checked="checked"' }} @endif required>
                    {{ __('male') }}
                    </label>
            </div>

            @if ($errors->has('gender'))
                <span class="help-block text-danger">
                    <strong>{{ $errors->first('gender') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password" class="control-label">{{ __('Password') }}</label>
            <input id="password" type="password" class="form-control" name="password" required>

            @if ($errors->has('password'))
                <span class="help-block text-danger">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            <label for="password-confirm" class="control-label">{{ __('Confirm Password') }}</label>
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
        </div>

        <div class="form-group">
            <div class="form-check">
                <label for="accept-toc" class="form-check-label">
                    <input id="accept-toc" type="checkbox" class="form-check-input" name="accept-toc" required>
                    {{ __('I hereby accept the terms and conditions.') }}
                </label>
            </div>

            @if ($errors->has('accept-toc'))
                <span class="help-block text-danger">
                    <strong>{{ $errors->first('accept-toc') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">
                {{ __('Register') }}
            </button>
        </div>
    </form>
@endsection
