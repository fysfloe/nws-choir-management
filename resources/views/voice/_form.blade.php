<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
    {{ Form::label('name', trans('Name'), ['class' => 'control-label']) }}
    {{ Form::text('name', old('name'), ['class' => 'form-control']) }}

    @if ($errors->has('name'))
        <span class="help-block text-danger">
            <strong>{{ $errors->first('name') }}</strong>
        </span>
    @endif
</div>

<div class="form-group">
    {{ Form::button(trans('Save Voice'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
</div>
