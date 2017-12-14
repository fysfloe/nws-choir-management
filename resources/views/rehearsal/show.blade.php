@extends('layouts.app')

@section('content')

    <header class="page-header">
        <h2>{{ $concert->title }}</h2>
        @permission('manageConcerts')
            <a class="btn btn-default btn-sm" href="{{ route('concert.edit', $concert) }}">
                {{ trans('Edit') }}
            </a>
        @endpermission
    </header>

@endsection
