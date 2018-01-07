@extends('layouts.app')

@section('content')

    <header class="page-header">
        <h2>{{ $concert->title }}</h2>

        <div class="btn-group accept-decline" role="group" aria-label="{{ __('Accept or decline') }}">
            <a href="{{ route('concert.accept', $concert) }}" class="btn btn-sm {{ $concert->promises->contains(Auth::user()) ? 'btn-success' : 'btn-default' }}">
                <span class="oi oi-check" aria-hidden="true"></span> {{ $concert->promises->contains(Auth::user()) ? __('You are attending!') : __('Attend') }}
            </a>
            <a href="{{ route('concert.decline', $concert) }}" class="btn btn-sm {{ $concert->denials->contains(Auth::user()) ? 'btn-danger' : 'btn-default' }}">
                <span class="oi oi-x" aria-hidden="true"></span> {{ $concert->denials->contains(Auth::user()) ? __('You are not attending.') : __('Decline') }}
            </a>
        </div>

        @permission('manageConcerts')
            <a class="btn btn-default btn-sm" href="{{ route('concert.edit', $concert) }}">
                {{ __('Edit') }}
            </a>
        @endpermission
    </header>

    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link @if ($tab === 'show') active @endif" href="{{ route('concert.show', $concert) }}" role="tab" aria-controls="info">
                <span class="oi oi-info"></span>&nbsp;
                {{ __('Info') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link @if ($tab === 'participants') active @endif" id="participants-tab" href="{{ route('concert.participants', $concert) }}" role="tab" aria-controls="participants">
                <span class="oi oi-people"></span>&nbsp;
                {{ __('Participants') }}
            </a>
        </li>
        @permission('manageConcerts')
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
