@extends('layouts.rehearsal')

@section('rehearsalContent')

    <!-- Info -->
    <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
        <ul class="margin-top">
            <li>
                <span class="oi oi-calendar" data-toggle="tooltip" title="{{ __('Date') }}"></span>&nbsp;
                {{ date_format(date_create($rehearsal->date), 'd.m.Y') }}
            </li>
            <li>
                <span class="oi oi-clock" data-toggle="tooltip" title="{{ __('Time') }}"></span>&nbsp;
                {{ date_format(date_create($rehearsal->start_time), 'H:i') }}–{{ date_format(date_create($rehearsal->end_time), 'H:i') }}
            </li>
            <li>
                <span class="oi oi-map-marker" data-toggle="tooltip" title="{{ __('Place') }}"></span>&nbsp;
                {{ $rehearsal->place }}
            </li>
        </ul>
    </div>

@endsection
