<div class="form-group{{ $errors->has('date') ? ' has-error' : '' }}">
    {{ Form::label('date', __('Date'), ['class' => 'control-label']) }}
    <input type="date" name="date" id="date" value="{{ old('date') ? old('date') : (isset($rehearsal) ? $rehearsal->date : null) }}" class="form-control">

    @if ($errors->has('date'))
        <span class="help-block text-danger">
            <strong>{{ $errors->first('date') }}</strong>
        </span>
    @endif
</div>

<div class="form-group{{ $errors->has('start_time') ? ' has-error' : '' }}">
    {{ Form::label('start_time', __('Start Time'), ['class' => 'control-label']) }}
    <input type="time" name="start_time" id="start_time" value="{{ old('start_time') ? old('start_time') : (isset($rehearsal) ? $rehearsal->start_time : '18:00') }}" class="form-control">

    @if ($errors->has('start_time'))
        <span class="help-block text-danger">
            <strong>{{ $errors->first('start_time') }}</strong>
        </span>
    @endif
</div>

<div class="form-group{{ $errors->has('end_time') ? ' has-error' : '' }}">
    {{ Form::label('end_time', __('End Time'), ['class' => 'control-label']) }}
    <input type="time" name="end_time" id="end_time" value="{{ old('end_time') ? old('end_time') : (isset($rehearsal) ? $rehearsal->end_time : '20:45') }}" class="form-control">

    @if ($errors->has('end_time'))
        <span class="help-block text-danger">
            <strong>{{ $errors->first('end_time') }}</strong>
        </span>
    @endif
</div>

<div class="form-group{{ $errors->has('place') ? ' has-error' : '' }}">
    {{ Form::label('place', __('Place'), ['class' => 'control-label']) }}
    {{ Form::text('place', old('place') ? old('place') : (isset($rehearsal) ? $rehearsal->place : 'Musisches Zentrum'), ['class' => 'form-control']) }}

    @if ($errors->has('place'))
        <span class="help-block text-danger">
            <strong>{{ $errors->first('place') }}</strong>
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
