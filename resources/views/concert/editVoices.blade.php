@extends('layouts.modal')

@section('title')
    {{ __('Edit voices') }}
@endsection()

@section('body')
    {{ Form::open(['route' => ['concert.saveVoices', $concert], 'method' => 'POST', 'class' => 'edit-voices']) }}

    @if (count($concert->voices) > 0)
        <small>{{ __('Currently added voices (click to remove)') }}</small>

        <ul class="voices">
        @foreach ($concert->voices as $voice)
            <a
            href="{{ route('concert.removeVoice', [$concert, $voice]) }}"
            onclick="return confirm('{{ __('Do you really want to remove this voice from the concert?') }}')"
            >
                <li
                    data-toggle="tooltip"
                    title="{{ __('Remove this voice from the concert') }}"
                    class="badge badge-pill badge-default"
                >
                        <strong>{{ $voice->name }}</strong>:
                        {{ $voice->pivot->number }}
                </li>
            </a>
        @endforeach
        </ul>
    @endif

    <div class="row voice">
        <div class="form-group col-8{{ $errors->has('voices') ? ' has-error' : '' }}">
            {{ Form::label('voices[]', __('Voice'), ['class' => 'control-label']) }}
            <select name="voices[]" class="form-control">
                @foreach ($voices as $voice)
                    <option value="{{ $voice->id }}"
                        @if ($concert->voices->contains($voice)) data-selected="true" data-number="{{ $concert->voices()->find($voice->id)->pivot->number }}" @endif
                    >
                        {{ $voice->name }}
                    </option>
                @endforeach
            </select>
            <small class="help-text text-muted" style="display: none">{{ __('This voice has already been added to the concert.') }}</small>
        </div><!-- .form-group -->

        <div class="form-group col-4{{ $errors->has('voiceNumber') ? ' has-error' : '' }}">
            {{ Form::label('voiceNumbers[]', __('Singers needed'), ['class' => 'control-label']) }}
            {{ Form::number('voiceNumbers[]', null, ['class' => 'form-control', 'min' => 1, 'max' => 99, 'placeholder' => __('e.g.') . ' 10')]) }}
        </div><!-- .form-group -->
    </div><!-- .row -->

    <a href="#" class="btn-link btn-sm add-voice">
        <span class="oi oi-plus"></span>&nbsp;
        {{ __('Add another voice') }}
    </a>

    {{ Form::close() }}
@endsection

@section('save')
    {{ __('Add voice(s)') }}
@endsection

@section('close')
    {{ __('Cancel') }}
@endsection
