@extends('layouts.concert')

@section('concertContent')

    <!-- Info -->
    <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
        <div class="row">
            <div class="col-8">
                <div class="description">
                    <h3>{{ __('Description') }}</h3>
                    @if ($concert->description)
                        {{ $concert->description }}
                    @else
                        <small class="text-muted">{{ __('No description added.') }}</small>
                    @endif
                </div>
            </div>

            <div class="col-4 side-box">
                <h3>{{ __('Date(s)') }}</h3>
                <ul class="dates">
                    @foreach ($concert->dates as $date)
                        <li>
                            {!! $date->__toString() !!}
                        </li>
                    @endforeach
                </ul>

                <h3 class="mt-4">{{ __('Rehearsals') }}</h3>
                @if (count($concert->rehearsals) > 0)
                    <ul class="rehearsals">
                    @foreach ($concert->rehearsals as $rehearsal)
                        <li>
                            {!! $rehearsal->__toString() !!}&nbsp;
                            <a href="{{ route('rehearsal.show', $rehearsal) }}">
                                <span class="oi oi-eye" data-toggle="tooltip" title="{{ __('Show') }}"></span>
                            </a>
                        </li>
                    @endforeach
                    </ul>
                @else
                    <small class="text-muted">{{ __('No rehearsals for this concert found.') }}</small>
                @endif

                @permission('manageRehearsals')
                <a class="btn btn-default btn-sm mt-2" href="{{ route('rehearsal.create', ['semester' => $concert->semester->id, 'concert' => $concert->id]) }}">
                    {{ __('Add a rehearsal') }}
                </a>
                @endpermission
            </div>
        </div>
    </div>

@endsection
