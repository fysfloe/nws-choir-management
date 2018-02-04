@extends('layouts.concert')

@section('concertContent')

    <!-- Participants -->
    <div class="tab-pane fade show active" id="participants" role="tabpanel" aria-labelledby="participants-tab">
        @include('user._filters')

        @permission('manageConcerts')
        <div class="clearfix">
            <a class="btn btn-default btn-sm" href="{{ route('concert.showAddUser', $concert) }}" data-toggle="modal" data-target="#mainModal">
                <span class="oi oi-plus"></span> {{ __('Add a participant') }}
            </a>
            <a class="btn btn-default btn-sm" href="{{ route('concert.exportParticipants', $concert, app('request')->all()) }}">
                <span class="oi oi-account-login"></span> {{ __('Export') }}
            </a>
        </div>
        @endpermission

        @if(count($participants) > 0)
            <div class="list-table">
                <header class="row">
                    <div class="col-md-8 has-checkbox">
                        <input type="checkbox" class="check-all" name="check-all-users" data-controls="users[]">&nbsp;

                        <div class="dropdown list-actions">
                            <a class="dropdown-toggle" href="#" role="button" id="userActions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            </a>

                            <div class="dropdown-menu" aria-labelledby="userActions">
                                <a class="dropdown-item" data-href="{{ route('concert.showSetUserVoices', ['concert' => $concert]) }}" href="{{ route('voice.showSetMulti') }}" data-toggle="modal" data-target="#mainModal"><span class="oi oi-pulse"></span> {{ __('Set Voice') }}</a>
                            </div>
                        </div>

                        {{ __('User') }}
                        <a class="list-sort {{ app('request')->input('sort') === 'surname' && app('request')->input('dir') === 'ASC' ? 'active' : '' }}" href="{{ route('users.index', ['sort' => 'surname', 'dir' => 'ASC']) }}">
                            <span class="oi oi-caret-top"></span>
                        </a>
                        <a class="list-sort {{ app('request')->input('sort') === 'surname' && app('request')->input('dir') === 'DESC' ? 'active' : '' }}" href="{{ route('users.index', ['sort' => 'surname', 'dir' => 'DESC']) }}">
                            <span class="oi oi-caret-bottom"></span>
                        </a>
                    </div>
                    <div class="col-md-4">
                        {{ __('Voice') }}
                        <a class="list-sort {{ app('request')->input('sort') === 'voice' && app('request')->input('dir') === 'ASC' ? 'active' : '' }}" href="{{ route('users.index', ['sort' => 'voice', 'dir' => 'ASC']) }}">
                            <span class="oi oi-caret-top"></span>
                        </a>
                        <a class="list-sort {{ app('request')->input('sort') === 'voice' && app('request')->input('dir') === 'DESC' ? 'active' : '' }}" href="{{ route('users.index', ['sort' => 'voice', 'dir' => 'DESC']) }}">
                            <span class="oi oi-caret-bottom"></span>
                        </a>
                    </div>
                </header>

                <ul class="users">
                    @foreach($participants as $user)
                        <li class="row">
                            <div class="col-md-8">
                                <input type="checkbox" name="users[]" value="{{ $user->id }}">&nbsp;
                                {{ $user->firstname }} {{ $user->surname }}
                                @permission('manageUsers')
                                    <a href="{{ route('profile.edit', $user->id) }}" class="btn-link btn-sm">
                                        <span class="oi oi-pencil" data-toggle="tooltip" title="{{ __('Edit Profile') }}"></span>
                                    </a>
                                @endpermission
                            </div>
                            <div class="col-md-4">
                                @if ($user->voiceName)
                                    {{ $user->voiceName }}
                                @else
                                    <small class="text-muted">({{ __('None set') }})</small>
                                @endif
                                @permission('manageConcerts')
                                    <a href="{{ route('concert.showSetUserVoice', ['concert' => $concert, 'user' => $user]) }}" data-toggle="modal" data-target="#mainModal" class="btn-link btn-sm">
                                        <span class="oi oi-pulse" data-toggle="tooltip" title="{{ __('Set Voice') }}"></span>
                                    </a>
                                @endpermission
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <div class="no-results">
                <small class="text-muted">{{ __('No participants matching your filters.') }}</small>
            </div>
        @endif
    </div>

@endsection
