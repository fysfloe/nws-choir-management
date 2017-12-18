@extends('layouts.app')

@section('content')

    <header class="page-header">
        <h2>{{ $voice->name }}</h2>
        @permission('manageVoices')
            <a class="btn btn-default btn-sm" href="{{ route('voices.edit', $voice) }}">
                {{ trans('Edit') }}
            </a>
        @endpermission
    </header>

@endsection
