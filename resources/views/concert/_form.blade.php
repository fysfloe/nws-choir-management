<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
    {{ Form::label('title', trans('Concert title'), ['class' => 'control-label']) }}
    {{ Form::text('title', old('title'), ['class' => 'form-control']) }}

    @if ($errors->has('title'))
        <span class="help-block">
            <strong>{{ $errors->first('title') }}</strong>
        </span>
    @endif
</div>

<div class="form-group">
    {{ Form::button(trans('Save Concert'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
</div>
