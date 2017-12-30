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
        <span class="text-muted">{{ trans('No active filters') }}</span>
    @endif

    <a class="show-filters" data-toggle="collapse" href="#filtersInner" aria-expanded="false" aria-controls="filtersInner">
        {{ trans('Show/hide filters') }}
    </a>

    <div id="filtersInner" class="collapse">
        {{ Form::open(['route' => 'concerts', 'method' => 'GET', 'class' => 'filter-form']) }}

        {{ Form::hidden('sort', app('request')->input('sort')) }}
        {{ Form::hidden('dir', app('request')->input('dir')) }}

        <div class="form-group">
            {{ Form::label('search', trans('Search'), ['class' => 'control-label']) }}
            {{ Form::text('search', old('search'), ['class' => 'form-control']) }}
        </div>

        <div class="form-group">
            {{ Form::label('show', trans('Old/New'), ['class' => 'control-label']) }}
            {{ Form::select('show', [
                'old' => trans('Only show old concerts'),
                'all' => trans('Show all concerts'),
                'new' => trans('Only show new concerts')
            ], old('show-old') ? old('show-old') : 'new', ['class' => 'form-control']) }}
        </div>

        {{ Form::close() }}
    </div>
</div>
