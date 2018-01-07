@extends('layouts.rehearsal')

@section('rehearsalContent')

    <!-- Participants -->
    <div class="tab-pane fade show active" id="participants" role="tabpanel" aria-labelledby="participants-tab">
        @include('user._filters')

        @permission('manageConcerts')
        <div class="clearfix">
            <a class="btn btn-default btn-sm" href="{{ route('rehearsal.showAddUser', $rehearsal) }}" data-toggle="modal" data-target="#mainModal">
                <span class="oi oi-plus"></span> {{ __('Add a participant') }}
            </a>
        </div>
        @endpermission

        @if(count($participants) > 0)
            <div class="list-table">
                <header class="row">
                    <div class="col-md-8">
                        {{ __('User') }}
                        <a class="list-sort {{ app('request')->input('sort') === 'surname' && app('request')->input('dir') === 'ASC' ? 'active' : '' }}" href="{{ route('rehearsal.participants', [$rehearsal, 'sort' => 'surname', 'dir' => 'ASC']) }}">
                            <span class="oi oi-caret-top"></span>
                        </a>
                        <a class="list-sort {{ app('request')->input('sort') === 'surname' && app('request')->input('dir') === 'DESC' ? 'active' : '' }}" href="{{ route('rehearsal.participants', [$rehearsal, 'sort' => 'surname', 'dir' => 'DESC']) }}">
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
                                {{ $user->firstname }} {{ $user->surname }}
                                <a href="{{ route('profile.edit', $user->id) }}" class="btn-link btn-sm">
                                    <span class="oi oi-pencil" data-toggle="tooltip" title="{{ __('Edit Profile') }}"></span>
                                </a>
                            </div>
                            <div class="col-md-4">
                                @if ($user->voice)
                                    {{ $user->voice->name }}
                                @else
                                    <small class="text-muted">({{ __('None set') }})</small>
                                @endif
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