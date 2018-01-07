<div class="form-group{{ $errors->has('start_date') ? ' has-error' : '' }}">
    {{ Form::label('start_date', __('Start Date'), ['class' => 'control-label']) }}
    <input type="date" class="form-control" name="start_date" id="start_date" value="{{ old('start_date') ? old('start_date') : (isset($semester) ? $semester->start_date : '') }}">

    @if ($errors->has('start_date'))
        <span class="help-block text-danger">
            <strong>{{ $errors->first('start_date') }}</strong>
        </span>
    @endif
</div><!-- .form-group -->

<div class="form-group{{ $errors->has('end_date') ? ' has-error' : '' }}">
    {{ Form::label('end_date', __('End Date'), ['class' => 'control-label']) }}
    <input type="date" class="form-control" name="end_date" id="end_date" value="{{ old('end_date') ? old('end_date') : (isset($semester) ? $semester->end_date : '') }}">

    @if ($errors->has('end_date'))
        <span class="help-block text-danger">
            <strong>{{ $errors->first('end_date') }}</strong>
        </span>
    @endif
</div><!-- .form-group -->

<div class="form-group">
    {{ Form::button(__('Save Semester'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
</div>
