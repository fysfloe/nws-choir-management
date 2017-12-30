@extends('layouts.app')

@section('content')

    <header class="page-header">
        <h2>{{ trans('Edit Profile') }}</h2>
        @if ($user->id === Auth::user()->id)
            <a class="btn btn-default btn-sm" href="{{ route('profile.changePassword') }}">
                {{ trans('Change Password') }}
            </a>
        @endif
    </header>

    {{ Form::open(['route' => ['profile.update', $user->id], 'method' => 'POST']) }}

    <div class="row">
        <div class="col">
            <h3>{{ trans('Personal Data') }}</h3>

            <div class="row">
                <div class="form-group col{{ $errors->has('firstname') ? ' has-error' : '' }}">
                    {{ Form::label('firstname', trans('First Name'), ['class' => 'control-label']) }}
                    {{ Form::text('firstname', old('firstname') ? old('firstname') : $user->firstname, ['class' => 'form-control']) }}

                    @if ($errors->has('firstname'))
                        <span class="help-block text-danger">
                            <strong>{{ $errors->first('firstname') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group col{{ $errors->has('surname') ? ' has-error' : '' }}">
                    {{ Form::label('surname', trans('Surname'), ['class' => 'control-label']) }}
                    {{ Form::text('surname', old('surname') ? old('surname') : $user->surname, ['class' => 'form-control']) }}

                    @if ($errors->has('surname'))
                        <span class="help-block text-danger">
                            <strong>{{ $errors->first('surname') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
                <div><label for="gender" class="control-label">{{ trans('Gender') }}</label></div>

                <div class="form-check-inline">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="gender" value="f" @if (old('gender') === 'f' || $user->gender === 'f') {{ 'checked="checked"' }} @endif required>
                        {{ trans('female') }}
                    </label>
                </div>
                <div class="form-check-inline">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="gender" value="m" @if (old('gender') === 'm' || $user->gender === 'm') {{ 'checked="checked"' }} @endif required>
                        {{ trans('male') }}
                    </label>
                </div>

                @if ($errors->has('gender'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('gender') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group {{ $errors->has('citizenship') ? ' has-error' : '' }}">
                {{ Form::label('citizenship', trans('Citizenship'), ['class' => 'control-label']) }}
                {{ Form::select('citizenship', $countries, old('citizenship') ? old('country') : $user->citizenship, ['class' => 'form-control']) }}

                @if ($errors->has('citizenship'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('citizenship') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group {{ $errors->has('voice') ? ' has-error' : '' }}">
                {{ Form::label('voice', trans('Voice'), ['class' => 'control-label']) }}
                {{ Form::select('voice', $voices, old('voice') ? old('voice') : ($user->voice() ? $user->voice()->id : null), ['class' => 'form-control']) }}
                <small class="form-text text-muted">{{ trans('This is your primary voice.') }}</small>

                @if ($errors->has('voice'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('voice') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="col">
            <h3>{{ trans('Contact Data') }}</h3>

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                {{ Form::label('email', trans('E-Mail Address'), ['class' => 'control-label']) }}
                {{ Form::email('email', old('email') ? old('email') : $user->email, ['class' => 'form-control']) }}

                @if ($errors->has('email'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                {{ Form::label('phone', trans('Phone'), ['class' => 'control-label']) }}
                {{ Form::text('phone', old('phone') ? old('phone') : $user->phone, ['class' => 'form-control']) }}

                @if ($errors->has('phone'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('phone') }}</strong>
                    </span>
                @endif
            </div>

            <h3>{{ trans('Address') }}</h3>

            <div class="form-group{{ $errors->has('street') ? ' has-error' : '' }}">
                {{ Form::label('street', trans('Street / No.'), ['class' => 'control-label']) }}
                {{ Form::text('street', old('street') ? old('street') : ($user->address ? $user->address->street : null), ['class' => 'form-control']) }}

                @if ($errors->has('street'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('street') }}</strong>
                    </span>
                @endif
            </div>

            <div class="row">
                <div class="form-group col-4{{ $errors->has('zip') ? ' has-error' : '' }}">
                    {{ Form::label('zip', trans('ZIP'), ['class' => 'control-label']) }}
                    {{ Form::number('zip', old('zip') ? old('zip') : ($user->address ? $user->address->zip : null), ['class' => 'form-control', 'min' => 1000, 'max' => 99999]) }}

                    @if ($errors->has('zip'))
                        <span class="help-block text-danger">
                            <strong>{{ $errors->first('zip') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group col-8{{ $errors->has('city') ? ' has-error' : '' }}">
                    {{ Form::label('city', trans('City'), ['class' => 'control-label']) }}
                    {{ Form::text('city', old('city') ? old('city') : ($user->address ? $user->address->city : null), ['class' => 'form-control']) }}

                    @if ($errors->has('city'))
                        <span class="help-block text-danger">
                            <strong>{{ $errors->first('city') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group {{ $errors->has('country_id') ? ' has-error' : '' }}">
                {{ Form::label('country', trans('Country'), ['class' => 'control-label']) }}
                {{ Form::select('country', $countries, old('country') ? old('country') : ($user->address && $user->address->country ? $user->address->country->id : null), ['class' => 'form-control']) }}

                @if ($errors->has('country_id'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('country_id') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="form-group">
        {{ Form::button(trans('Save Profile'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
    </div>

    {{ Form::close() }}

@endsection
