@extends('layouts.app')

@section('content')
    <header class="page-header">
        <h2>Login</h2>
    </header>
    <form method="POST" action="{{ route('login') }}">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
            <label for="firstname" class="control-label">{{ __('First Name') }}</label>

            <div>
                <input id="firstname" type="text" class="form-control" name="firstname" value="{{ old('firstname') }}" required autofocus>

                @if ($errors->has('firstname'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('firstname') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <label for="surname" class="control-label">{{ __('Surname') }}</label>

            <div>
                <input id="surname" type="text" class="form-control" name="surname" value="{{ old('surname') }}" required>

                @if ($errors->has('surname'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('surname') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password" class="control-label">{{ __('Password') }}</label>

            <div>
                <input id="password" type="password" class="form-control" name="password" required>

                @if ($errors->has('password'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                </label>
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">
                Login
            </button>

            <a class="btn btn-link" href="{{ route('password.request') }}">
                Forgot Your Password?
            </a>
        </div>
    </form>
@endsection
