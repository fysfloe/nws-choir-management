<div class="filters">
    @if (count($activeFilters) > 0)
        <ul class="active-filters">
            @foreach ($activeFilters as $key => $val)
                <li class="badge badge-pill badge-default" data-field="{{ $key }}">
                    <strong>{{ $key }}</strong>:
                    {{ $val }}
                </li>
            @endforeach
        </ul>
    @else
        <span class="text-muted">{{ __('No active filters') }}</span>
    @endif

    <a class="show-filters" data-toggle="collapse" href="#filtersInner" aria-expanded="false" aria-controls="filtersInner">
        {{ __('Show/hide filters') }}
    </a>

    <div id="filtersInner" class="collapse">
        @yield('filtersInner')
    </div>
</div>
