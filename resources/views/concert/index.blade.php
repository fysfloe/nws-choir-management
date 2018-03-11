@extends('layouts.app')

@section('content')

    <header class="page-header">
        <h2>{{ __('Concerts') }}</h2>
        @permission('manageConcerts')
            <a class="btn btn-default btn-sm" href="{{ route('concert.create') }}">
                {{ __('New Concert') }}
            </a>
        @endpermission
    </header>

    @include('concert._filters')

    @if(count($concerts) > 0)
        <div class="list-table">
            <header class="row">
                <div class="col-md-1">
                    <small class="text-muted">#</small>
                    <a class="list-sort {{ app('request')->input('sort') === 'id' && app('request')->input('dir') === 'ASC' ? 'active' : '' }}" href="{{ route('concerts', ['sort' => 'id', 'dir' => 'ASC']) }}">
                        <span class="oi oi-caret-top"></span>
                    </a>
                    <a class="list-sort {{ app('request')->input('sort') === 'id' && app('request')->input('dir') === 'DESC' ? 'active' : '' }}" href="{{ route('concerts', ['sort' => 'id', 'dir' => 'DESC']) }}">
                        <span class="oi oi-caret-bottom"></span>
                    </a>
                </div>
                <div class="col-md-4">
                    {{ __('Title') }}
                    <a class="list-sort {{ app('request')->input('sort') === 'title' && app('request')->input('dir') === 'ASC' ? 'active' : '' }}" href="{{ route('concerts', ['sort' => 'title', 'dir' => 'ASC']) }}">
                        <span class="oi oi-caret-top"></span>
                    </a>
                    <a class="list-sort {{ app('request')->input('sort') === 'title' && app('request')->input('dir') === 'DESC' ? 'active' : '' }}" href="{{ route('concerts', ['sort' => 'title', 'dir' => 'DESC']) }}">
                        <span class="oi oi-caret-bottom"></span>
                    </a>
                </div>
                <div class="col-md-3">
                    {{ __('Date') }}
                    <a class="list-sort {{ app('request')->input('sort') === 'date' && app('request')->input('dir') === 'ASC' ? 'active' : '' }}" href="{{ route('concerts', ['sort' => 'date', 'dir' => 'ASC']) }}">
                        <span class="oi oi-caret-top"></span>
                    </a>
                    <a class="list-sort {{ app('request')->input('sort') === 'date' && app('request')->input('dir') === 'DESC' ? 'active' : '' }}" href="{{ route('concerts', ['sort' => 'date', 'dir' => 'DESC']) }}">
                        <span class="oi oi-caret-bottom"></span>
                    </a>
                </div>
                <div class="col-md-2">
                    @permission('manageConcerts')
                        {{ __('Created by') }}
                    @endpermission
                </div>
                <div class="col-md-2">&nbsp;</div>
            </header>

            <ul class="concerts">
                @foreach($concerts as $concert)
                    <li class="row">
                        <div class="col-md-1">
                            <small class="text-muted">{{ $concert->id }}</small>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('concert.show', $concert->slug) }}">{{ $concert->title }}</a>
                        </div>
                        <div class="col-md-3">
                            <small>
                                <span class="oi oi-calendar text-muted"></span>&nbsp;{{ date_format(date_create($concert->date), 'd.m.Y') }}&nbsp;
                                <span class="oi oi-clock text-muted"></span>&nbsp;{{ date_format(date_create($concert->start_time), 'H:i') }}&nbsp;
                                @if ($concert->end_time)
                                    – {{ date_format(date_create($concert->end_time), 'H:i') }}
                                @endif
                            </small>
                        </div>
                        <div class="col-md-2">
                            @permission('manageConcerts')
                                {{ $concert->creator->firstname }} {{ $concert->creator->surname }}
                            @endpermission
                        </div>
                        <div class="col-md-2">
                            @permission('manageConcerts')
                                {!! Form::open(['onsubmit' => 'return confirm("' . __('Do you really want to delete this concert?') . '")', 'class' => 'form-inline', 'method' => 'DELETE', 'route' => ['concert.delete', $concert->id]]) !!}
                                    <button type="submit" class="btn-link text-danger">
                                        <span class="oi oi-trash" title="{{ __('Delete') }}" aria-hidden="true"></span>
                                    </button>
                                {!! Form::close() !!}
                            @endpermission
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="no-results">
            <small class="text-muted">{{ __('No concerts found.') }}</small>
        </div>
    @endif

@endsection
