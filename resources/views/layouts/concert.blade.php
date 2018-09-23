@extends('layouts.app')

@section('content')

    <header class="page-header">
            <div>
                <h2>
                    {{ $concert->title }}
                    @permission('manageConcerts')
                        <a class="btn btn-link" href="{{ route('concert.edit', $concert) }}">
                            <span class="oi oi-pencil" data-toggle="tooltip" title="{{ __('Edit') }}"></span>
                        </a>
                    @endpermission
                </h2>
            </div>
        
            <accept-decline
                accept-route="{{ route('concert.accept', $concert) }}"
                decline-route="{{ route('concert.decline', $concert) }}"
                :accepted="{{ json_encode($concert->promises->contains(Auth::user())) }}"
                :declined="{{ json_encode($concert->denials->contains(Auth::user())) }}"
                :texts="{{ json_encode([
                    'acceptOrDecline' => __('Accept or decline'),
                    'attending' => __('You are attending!'),
                    'accept' => __('Accept'),
                    'notAttending' => __('You are not attending.'),
                    'decline' => __('Decline')
                ]) }}"
            >
            </accept-decline>
    </header>

    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link @if ($tab === 'show') active @endif" href="{{ route('concert.show', $concert) }}" role="tab" aria-controls="info">
                <span class="oi oi-info"></span>&nbsp;
                {{ __('Info') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link @if ($tab === 'comments') active @endif" href="{{ route('concert.comments', $concert) }}" role="tab" aria-controls="info">
                <span class="oi oi-comment-square"></span>&nbsp;
                {{ __('Comments') }} ({{ count($concert->comments) }})
            </a>
        </li>
        @permission('manageConcerts')
        <li class="nav-item">
            <a class="nav-link @if ($tab === 'participants') active @endif" id="participants-tab" href="{{ route('concert.participants', $concert) }}" role="tab" aria-controls="participants">
                <span class="oi oi-people"></span>&nbsp;
                {{ __('Participants') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link @if ($tab === 'voices') active @endif" id="voices-tab" href="{{ route('concert.voices', $concert) }}" role="tab" aria-controls="voices">
                <span class="oi oi-pulse"></span>&nbsp;
                {{ __('Voices') }}
            </a>
        </li>
        @endpermission
    </ul>

    @yield('concertContent')

@endsection
