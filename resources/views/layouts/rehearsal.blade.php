@extends('layouts.app')

@section('content')

    <header class="page-header">
        <h2>{!! $rehearsal->__toString() !!}</h2>

        <div class="btn-group accept-decline" role="group" aria-label="{{ __('Accept or decline') }}">
            <a href="{{ route('rehearsal.accept', $rehearsal) }}" class="btn btn-sm {{ $rehearsal->promises->contains(Auth::user()) ? 'btn-success' : 'btn-default' }}">
                <span class="oi oi-check" aria-hidden="true"></span> {{ $rehearsal->promises->contains(Auth::user()) ? __('You are attending!') : __('Attend') }}
            </a>
            <a href="{{ route('rehearsal.decline', $rehearsal) }}" class="btn btn-sm {{ $rehearsal->denials->contains(Auth::user()) ? 'btn-danger' : 'btn-default' }}">
                <span class="oi oi-x" aria-hidden="true"></span> {{ $rehearsal->denials->contains(Auth::user()) ? __('You are not attending.') : __('Decline') }}
            </a>
        </div>

        @permission('manageRehearsals')
            <a class="btn btn-default btn-sm" href="{{ route('rehearsal.edit', $rehearsal) }}">
                {{ __('Edit') }}
            </a>
        @endpermission
    </header>

    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link @if ($tab === 'show') active @endif" href="{{ route('rehearsal.show', $rehearsal) }}" role="tab" aria-controls="info">
                <span class="oi oi-info"></span>&nbsp;
                {{ __('Info') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link @if ($tab === 'participants') active @endif" id="participants-tab" href="{{ route('rehearsal.participants', $rehearsal) }}" role="tab" aria-controls="participants">
                <span class="oi oi-people"></span>&nbsp;
                {{ __('Participants') }}
            </a>
        </li>
    </ul>

    @yield('rehearsalContent')

@endsection
