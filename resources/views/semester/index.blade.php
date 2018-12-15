@extends('layouts.app')

@section('content')

    <header class="page-header">
        <h2>{{ __('Semesters') }}</h2>
        @permission('manageSemesters')
            <a class="btn btn-default btn-sm" href="{{ route('semesters.create') }}">
                {{ __('New Semester') }}
            </a>
        @endpermission
    </header>

    <semester-list
            :can-manage-semesters="{{ Auth::user()->can('manageSemesters') ? 1 : 0 }}"
            fetch-action="{{ route('semester.loadItems') }}"
            :sort-options="{{ json_encode([
                'start_date' => __('Date')
            ]) }}"
            :actions="{{ json_encode(['remove', 'edit']) }}"
            :user="{{ Auth::user() }}"
    ></semester-list>

@endsection
