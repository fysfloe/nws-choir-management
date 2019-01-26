@extends('layouts.app')

@section('content')

    <header class="page-header">
        <h2>{{ __('Users') }}</h2>
        @permission('manageUsers')
        <div class="main-actions">
            <a class="btn btn-default btn-sm" href="{{ route('users.create') }}">
                {{ __('New User') }}
            </a>
            <a class="btn btn-default btn-sm" href="{{ route('users.export', app('request')->all()) }}">
                <span class="oi oi-account-login"></span> {{ __('Export') }}
            </a>
        </div>
        @endpermission
    </header>

    <user-list
            :concerts="{{ json_encode($concerts) }}"
            :users="{{ json_encode($users) }}"
            :can-manage-users="{{ Auth::user()->can('manageUsers') }}"
            :show-roles="true"
            :voices="{{ json_encode($voices) }}"
            fetch-users-action="{{ route('users.load') }}"
            :sort-options="{{ json_encode([
            'firstname' => __('Firstname'),
            'surname' => __('Surname'),
            'voice' => __('Voice'),
            'id' => __('Created at')
        ]) }}"
            set-voice-route="{{ route('voice.showSet') }}"
            :actions="{{ json_encode([
            'setVoice', 'editProfile', 'setRole', 'archive'
        ]) }}"
            :filters="{{ json_encode([
            'search' => '',
            'voices' => [],
            'concerts' => [],
            'ageFrom' => '',
            'ageTo' => '',
            'sort' => 'surname',
            'dir' => 'ASC'
        ])  }}"
    ></user-list>

@endsection
