@extends('layouts.app')

@section('content')

    <header class="page-header">
        <h2>Concerts</h2>
        @permission('manageConcerts')
            <a class="btn btn-default btn-sm" href="{{ route('concert.create') }}">
                {{ trans('New Concert') }}
            </a>
        @endpermission
    </header>

    @if(count($concerts) > 0)
        <ul class="concerts">
            @foreach($concerts as $concert)
                <li class="row">
                    <div class="col-md-8">
                        <a href="{{ route('concert.show', $concert->id) }}">{{ $concert->title }}</a>
                    </div>
                    <div class="col-md-4">
                        {{ $concert->creator->firstname }} {{ $concert->creator->surname }}
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <div class="no-results">{{ trans('No concerts found.') }}</div>
    @endif

@endsection
