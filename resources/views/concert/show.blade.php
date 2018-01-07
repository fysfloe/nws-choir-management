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
            </div>
        </div>
    </div>

@endsection
