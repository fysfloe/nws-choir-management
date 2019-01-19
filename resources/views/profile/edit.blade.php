@extends('layouts.app')

@section('content')

    {{ Form::open(['route' => ['profile.update', $user], 'method' => 'POST', 'files' => true]) }}

    <header class="page-header">
            @if ($user->id === Auth::user()->id)
                <h2>{{ __('Edit Profile') }}</h2>
                <a class="btn btn-default btn-sm" href="{{ route('profile.changePassword') }}">
                    {{ __('Change Password') }}
                </a>
            @else
                <h2>{{ __('Edit Profile') }}: <span class="text-muted">{{ $user->firstname }} {{ $user->surname }}</span></h2>
            @endif
    </header>

    <div class="row">
        <div class="col">
            <h3>{{ __('Personal Data') }}</h3>

            <div class="row">
                <div class="form-group col{{ $errors->has('firstname') ? ' has-error' : '' }}">
                    {{ Form::label('firstname', __('First Name'), ['class' => 'control-label']) }}
                    {{ Form::text('firstname', old('firstname') ? old('firstname') : $user->firstname, ['class' => 'form-control']) }}

                    @if ($errors->has('firstname'))
                        <span class="help-block text-danger">
                            <strong>{{ $errors->first('firstname') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group col{{ $errors->has('surname') ? ' has-error' : '' }}">
                    {{ Form::label('surname', __('Surname'), ['class' => 'control-label']) }}
                    {{ Form::text('surname', old('surname') ? old('surname') : $user->surname, ['class' => 'form-control']) }}

                    @if ($errors->has('surname'))
                        <span class="help-block text-danger">
                            <strong>{{ $errors->first('surname') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                {{ Form::label('username', __('Username'), ['class' => 'control-label']) }}
                {{ Form::text('username', old('username') ? old('username') : $user->username, ['class' => 'form-control']) }}

                @if ($errors->has('username'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('username') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group {{ $errors->has('birthdate') ? ' has-error' : '' }}">
                {{ Form::label('birthdate', __('Birthdate'), ['class' => 'control-label']) }}
                {{ Form::date('birthdate', old('birthdate') ? old('birthdate') : $user->birthdate, ['class' => 'form-control']) }}

                @if ($errors->has('birthdate'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('birthdate') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group {{ $errors->has('citizenship') ? ' has-error' : '' }}">
                {{ Form::label('citizenship', __('Citizenship'), ['class' => 'control-label']) }}
                {{ Form::select('citizenship', $countries, old('citizenship') ? old('citizenship') : ($user->citizenship ? $user->citizenship->id : 40), ['class' => 'form-control']) }}

                @if ($errors->has('citizenship'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('citizenship') }}</strong>
                    </span>
                @endif
            </div>

            @permission('manageUsers')
            <div class="form-group {{ $errors->has('non_singing') ? ' has-error' : '' }}">
                <label class="control-label">
                    {{ Form::checkbox('non_singing', 1, old('non_singing') ? old('non_singing') : $user->non_singing) }}
                    &nbsp;
                    {{ __('This user is not a singer.') }}<br>
                    <small class="form-text text-muted">{{ __('This user will not appear in the lists for rehearsals, concerts and projects.') }}</small>
                </label>

                @if ($errors->has('non_singing'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('non_singing') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group {{ $errors->has('voice_id') ? ' has-error' : '' }}">
                {{ Form::label('voice_id', __('Voice'), ['class' => 'control-label']) }}
                {{ Form::select('voice_id', $voices, old('voice_id') ? old('voice_id') : ($user->voice ? $user->voice->id : null), ['class' => 'form-control']) }}
                <small class="form-text text-muted">{{ __('This is your primary voice.') }}</small>

                @if ($errors->has('voice_id'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('voice_id') }}</strong>
                    </span>
                @endif
            </div>
            @endpermission

            <div class="form-group {{ $errors->has('avatar') ? ' has-error': '' }}">
                <picture-input 
                    ref="avatar"
                    name="avatar"
                    id="avatar"
                    radius="50" 
                    width="150"
                    height="150"
                    accept="image/jpeg,image/png,image/jpg" 
                    size="10" 
                    button-class="btn-sm btn-primary"
                    prefill="{{ $user->avatar ? asset('storage/avatars/' . $user->avatar) : '' }}"
                >
                </picture-input>

                @if ($errors->has('avatar'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('avatar') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="col">
            <h3>{{ __('Contact Data') }}</h3>

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                {{ Form::label('email', __('E-Mail Address'), ['class' => 'control-label']) }}
                {{ Form::email('email', old('email') ? old('email') : $user->email, ['class' => 'form-control']) }}

                @if ($errors->has('email'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                {{ Form::label('phone', __('Phone'), ['class' => 'control-label']) }}
                {{ Form::text('phone', old('phone') ? old('phone') : $user->phone, ['class' => 'form-control']) }}

                @if ($errors->has('phone'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('phone') }}</strong>
                    </span>
                @endif
            </div>

            <h3>{{ __('Address') }}</h3>

            <div class="form-group{{ $errors->has('street') ? ' has-error' : '' }}">
                {{ Form::label('street', __('Street / No.'), ['class' => 'control-label']) }}
                {{ Form::text('street', old('street') ? old('street') : ($user->address ? $user->address->street : null), ['class' => 'form-control']) }}

                @if ($errors->has('street'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('street') }}</strong>
                    </span>
                @endif
            </div>

            <div class="row">
                <div class="form-group col-4{{ $errors->has('zip') ? ' has-error' : '' }}">
                    {{ Form::label('zip', __('ZIP'), ['class' => 'control-label']) }}
                    {{ Form::number('zip', old('zip') ? old('zip') : ($user->address ? $user->address->zip : null), ['class' => 'form-control', 'min' => 1000, 'max' => 99999]) }}

                    @if ($errors->has('zip'))
                        <span class="help-block text-danger">
                            <strong>{{ $errors->first('zip') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group col-8{{ $errors->has('city') ? ' has-error' : '' }}">
                    {{ Form::label('city', __('City'), ['class' => 'control-label']) }}
                    {{ Form::text('city', old('city') ? old('city') : ($user->address ? $user->address->city : null), ['class' => 'form-control']) }}

                    @if ($errors->has('city'))
                        <span class="help-block text-danger">
                            <strong>{{ $errors->first('city') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group {{ $errors->has('country_id') ? ' has-error' : '' }}">
                {{ Form::label('country_id', __('Country'), ['class' => 'control-label']) }}
                {{ Form::select('country_id', $countries, old('country_id') ? old('country_id') : ($user->address && $user->address->country ? $user->address->country->id : 40), ['class' => 'form-control']) }}

                @if ($errors->has('country_id'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('country_id') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="form-group">
        {{ Form::button(__('Save Profile'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
    </div>

    {{ Form::close() }}

@endsection
