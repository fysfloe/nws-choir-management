@extends('layouts.rehearsal')

@section('rehearsalContent')

    <!-- Info -->
    <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
        <div class="row">
            <div class="col-8">
                <div class="details">
                    <h3>{{ __('Details')  }}</h3>
                    <ul class="margin-top no-list-style">
                        <li>
                            <span class="oi oi-calendar" data-toggle="tooltip" title="{{ __('Date') }}"></span>&nbsp;
                            {{ date_format(date_create($rehearsal->date), 'd.m.Y') }}
                        </li>
                        <li>
                            <span class="oi oi-clock" data-toggle="tooltip" title="{{ __('Time') }}"></span>&nbsp;
                            {{ date_format(date_create($rehearsal->start_time), 'H:i') }}
                            –{{ date_format(date_create($rehearsal->end_time), 'H:i') }}
                        </li>
                        <li>
                            <span class="oi oi-map-marker" data-toggle="tooltip" title="{{ __('Place') }}"></span>&nbsp;
                            {{ $rehearsal->place }}
                        </li>
                    </ul>
                </div>

                <div class="description">
                    <h3>{{ __('Description') }}</h3>
                    @if ($rehearsal->description)
                        {!! nl2br($rehearsal->description) !!}
                    @else
                        <small class="text-muted">{{ __('No description added.') }}</small>
                    @endif
                </div>
            </div>
            <div class="col-4 side-box">
                <h3>{{ __('Project') }}</h3>
                <a href="{{ route('project.show', $rehearsal->project) }}">{{ $rehearsal->project->title }}</a>

                @if (count($rehearsal->project->concerts) > 1)
                    <h3 class="mt-4">{{ __('Concerts') }}</h3>

                    <ul class="concerts">
                        @foreach ($rehearsal->project->concerts as $concert)
                            <a href="{{ route('concert.show', $concert) }}">
                                <li>
                                    <div>
                                        <strong>{{ $concert->title }}</strong><br>
                                        <span class="oi oi-calendar text-muted"></span>&nbsp;{{ date_format(date_create($concert->date), 'd.m.Y') }}
                                        &nbsp;
                                        <span class="oi oi-clock text-muted"></span>&nbsp;{{ date_format(date_create($concert->start_time), 'H:i') }}
                                        &nbsp;
                                    </div>
                                    @if ($concert->getDateTime() > new \DateTime())
                                        <accept-decline
                                                accept-route="{{ route('concert.accept', $concert) }}"
                                                decline-route="{{ route('concert.decline', $concert) }}"
                                                :accepted="{{ json_encode($concert->promises->contains(Auth::user())) }}"
                                                :declined="{{ json_encode($concert->denials->contains(Auth::user())) }}"
                                        >
                                        </accept-decline>
                                    @else
                                        <span class="accept-decline oi oi-media-record {{ $concert->promises->contains(Auth::user()) ? 'text-success' : ($concert->denials->contains(Auth::user()) ? 'text-danger' : 'text-muted') }}"></span>
                                    @endif
                                </li>
                            </a>
                        @endforeach
                    </ul>
                @endif

                @if (count($rehearsal->project->rehearsals) > 1)
                    <h3 class="mt-4">{{ __('Other rehearsals in this project') }}</h3>
                    <ul class="rehearsals">
                        @foreach ($rehearsal->project->rehearsals as $otherRehearsal)
                            @if ($otherRehearsal->id !== $rehearsal->id)
                                <a href="{{ route('rehearsal.show', $otherRehearsal) }}">
                                    <li>
                                        <span>{!! $otherRehearsal->twoLineString() !!}</span>
                                        @if ($otherRehearsal->getDateTime() > new \DateTime())
                                            <accept-decline
                                                    accept-route="{{ route('rehearsal.accept', $otherRehearsal) }}"
                                                    decline-route="{{ route('rehearsal.decline', $otherRehearsal) }}"
                                                    :accepted="{{ json_encode($otherRehearsal->promises->contains(Auth::user())) }}"
                                                    :declined="{{ json_encode($otherRehearsal->denials->contains(Auth::user())) }}"
                                            >
                                            </accept-decline>
                                        @endif
                                    </li>
                                </a>
                            @endif
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>

@endsection
