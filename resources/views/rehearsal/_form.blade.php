<div class="form-group{{ $errors->has('date') ? ' has-error' : '' }}">
    {{ Form::label('date', trans('Date'), ['class' => 'control-label']) }}
    <input type="datetime-local" name="date" id="date" value="{{ old('date') }}" class="form-control">

    @if ($errors->has('date'))
        <span class="help-block text-danger">
            <strong>{{ $errors->first('date') }}</strong>
        </span>
    @endif
</div>

<div class="form-group">
    {{ Form::button(trans('Save Rehearsal'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
</div>
