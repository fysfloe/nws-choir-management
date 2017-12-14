@extends('layouts.app')

@section('content')

    <header class="page-header">
        <h2>{{ trans('Concerts') }}</h2>
        @permission('manageConcerts')
            <a class="btn btn-default btn-sm" href="{{ route('concert.create') }}">
                {{ trans('New Concert') }}
            </a>
        @endpermission
    </header>

    @if(count($concerts) > 0)
        <div class="list-table">
            <header class="row">
                <div class="col-md-6">{{ trans('Title') }}</div>
                <div class="col-md-2">{{ trans('Date') }}</div>
                <div class="col-md-2">{{ trans('Created by') }}</div>
                <div class="col-md-2">&nbsp;</div>
            </header>

            <ul class="concerts">
                @foreach($concerts as $concert)
                    <li class="row">
                        <div class="col-md-6">
                            <a href="{{ route('concert.show', $concert->slug) }}">{{ $concert->title }}</a>
                        </div>
                        <div class="col-md-2">
                            {{ $concert->dates()->first()->date->format('d.m.Y') }}
                            {{ $concert->dates()->first()->date->format('H:i') }}
                            <a href="#">
                                <span class="oi oi-plus" title="{{ trans('Add date') }}" aria-hidden="true"></span>
                            </a>
                        </div>
                        <div class="col-md-2">
                            {{ $concert->creator->firstname }} {{ $concert->creator->surname }}
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('concert.edit', $concert) }}">
                                <span class="oi oi-pencil" title="{{ trans('Edit') }}" aria-hidden="true"></span>
                            </a>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="no-results">{{ trans('No concerts found.') }}</div>
    @endif

@endsection
