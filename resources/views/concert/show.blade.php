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

    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="info-tab" data-toggle="tab" href="#info" role="tab" aria-controls="info" aria-selected="true">
                <span class="oi oi-info"></span>&nbsp;
                {{ trans('Info') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="participants-tab" data-toggle="tab" href="#participants" role="tab" aria-controls="participants" aria-selected="false">
                <span class="oi oi-people"></span>&nbsp;
                {{ trans('Participants') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <!-- Info -->
        <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
            <h4>{{ trans('Dates') }}</h4>
            <ul class="dates">
                @foreach ($concert->dates as $date)
                    <li>{{ $date->date }}</li>
                @endforeach
            </ul>
        </div>

        <!-- Participants -->
        <div class="tab-pane fade" id="participants" role="tabpanel" aria-labelledby="participants-tab">

        </div>
    </div>

@endsection
