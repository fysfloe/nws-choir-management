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
                    <div class="col-md-6">
                        {{ __('User') }}
                        <a class="list-sort {{ app('request')->input('sort') === 'surname' && app('request')->input('dir') === 'ASC' ? 'active' : '' }}" href="{{ route('rehearsal.participants', [$rehearsal, 'sort' => 'surname', 'dir' => 'ASC']) }}">
                            <span class="oi oi-caret-top"></span>
                        </a>
                        <a class="list-sort {{ app('request')->input('sort') === 'surname' && app('request')->input('dir') === 'DESC' ? 'active' : '' }}" href="{{ route('rehearsal.participants', [$rehearsal, 'sort' => 'surname', 'dir' => 'DESC']) }}">
                            <span class="oi oi-caret-bottom"></span>
                        </a>
                    </div>
                    <div class="col-md-3">
                        {{ __('Voice') }}
                        <a class="list-sort {{ app('request')->input('sort') === 'voice' && app('request')->input('dir') === 'ASC' ? 'active' : '' }}" href="{{ route('users.index', ['sort' => 'voice', 'dir' => 'ASC']) }}">
                            <span class="oi oi-caret-top"></span>
                        </a>
                        <a class="list-sort {{ app('request')->input('sort') === 'voice' && app('request')->input('dir') === 'DESC' ? 'active' : '' }}" href="{{ route('users.index', ['sort' => 'voice', 'dir' => 'DESC']) }}">
                            <span class="oi oi-caret-bottom"></span>
                        </a>
                    </div>
                    <div class="col-md-3">
                        &nbsp;
                    </div>
                </header>

                <ul class="users">
                    @foreach($participants as $user)
                        <li class="row">
                            <div class="col-md-6">
                                {{ $user->firstname }} {{ $user->surname }}
                                <a href="{{ route('profile.edit', $user->id) }}" class="btn-link btn-sm">
                                    <span class="oi oi-pencil" data-toggle="tooltip" title="{{ __('Edit Profile') }}"></span>
                                </a>
                            </div>
                            <div class="col-md-3">
                                @if ($user->voice)
                                    {{ $user->voice->name }}
                                @else
                                    <small class="text-muted">({{ __('None set') }})</small>
                                @endif
                            </div>
                            <div class="col-md-3">
                                @if ($rehearsal->date >= date_format((new \DateTime()), 'Y-m-d'))
                                    <div class="btn-group disabled" role="group" aria-label="{{ __('Set present, excused or unexcused') }}">
                                        <a href="{{ route('rehearsal.confirm', ['rehearsal' => $rehearsal, 'user' => $user]) }}" data-clicked-class="btn-success" data-unclicked-class="btn-default" class="ajax btn btn-sm {{ $rehearsal->confirmed->contains(Auth::user()) ? 'btn-success clicked' : 'btn-default' }}">
                                            <span class="oi oi-check" aria-hidden="true"></span> <span class="info-text">{{ __('Present') }}</span>
                                        </a>
                                        <a href="{{ route('rehearsal.excuse', ['rehearsal' => $rehearsal, 'user' => $user]) }}" data-clicked-class="btn-warning" data-unclicked-class="btn-default" class="ajax btn btn-sm {{ $rehearsal->excused->contains(Auth::user()) ? 'btn-warning clicked' : 'btn-default' }}">
                                            <span class="oi oi-medical-cross" aria-hidden="true"></span> <span class="info-text">{{ __('Excused') }}</span>
                                        </a>
                                        <a href="{{ route('rehearsal.setUnexcused', ['rehearsal' => $rehearsal, 'user' => $user]) }}" data-clicked-class="btn-danger" data-unclicked-class="btn-default" class="ajax btn btn-sm {{ $rehearsal->unexcused->contains(Auth::user()) ? 'btn-danger clicked' : 'btn-default' }}">
                                            <span class="oi oi-x" aria-hidden="true"></span> <span class="info-text">{{ __('Unexcused') }}</span>
                                        </a>
                                    </div>
                                @else
                                    <small class="{{ $rehearsal->confirmed->contains(Auth::user()) ? 'text-success' : ($rehearsal->excused->contains(Auth::user()) ? 'text-warning' : ($rehearsal->unexcused->contains(Auth::user()) ? 'text-danger' : 'text-muted')) }}">
                                        {{ $rehearsal->confirmed->contains(Auth::user()) ? __('Present') : ($rehearsal->excused->contains(Auth::user()) ? __('Excused') : ($rehearsal->unexcused->contains(Auth::user()) ? __('Unexcused') : __('No info'))) }}
                                    </small>
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
