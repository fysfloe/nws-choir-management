@extends('layouts.app')

@section('content')
    <header class="page-header">
        <h2>{{ trans('Dashboard') }}</h2>
    </header>
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="container">
        <div class="row">
            @if (count(Auth::user()->voices) === 0)
                {{ trans('You didn\'t set your voice yet.') }}&nbsp;
                <a href="{{ route('voice.showSet', Auth::user()) }}" data-toggle="modal" data-target="#mainModal">{{ trans('Do it now!') }}</a>
            @endif
        </div>
    </div>
@endsection
