@extends('layouts.app')

@section('content')
    <header class="page-header">
        <h2>Login</h2>
    </header>
    <form method="POST" action="{{ route('login') }}">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
            <label for="username" class="control-label">{{ __('Username') }}</label>

            <div>
                <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required autofocus>

                @if ($errors->has('username'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('username') }}</strong>
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
