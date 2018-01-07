<div class="form-group{{ $errors->has('date') ? ' has-error' : '' }}">
    {{ Form::label('date', __('Date'), ['class' => 'control-label']) }}
    <input type="datetime-local" name="date" id="date" value="{{ old('date') ? old('date') : (isset($rehearsal) ? $rehearsal->date : null) }}" class="form-control">

    @if ($errors->has('date'))
        <span class="help-block text-danger">
            <strong>{{ $errors->first('date') }}</strong>
        </span>
    @endif
</div>

<div class="form-group{{ $errors->has('semester_id') ? ' has-error' : '' }}">
    {{ Form::label('semester_id', __('Semester'), ['class' => 'control-label']) }}
    {{ Form::select('semester_id', $semesters, old('semester_id') ? old('semester_id') : (isset($rehearsal) ? $rehearsal->semester_id : ($app->request->get('semester') ? $app->request->get('semester') : null)), ['class' => 'form-control']) }}

    @if ($errors->has('semester_id'))
        <span class="help-block text-danger">
            <strong>{{ $errors->first('semester_id') }}</strong>
        </span>
    @endif
</div><!-- .form-group -->

<div class="form-group">
    {{ Form::button(__('Save Rehearsal'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
</div>
