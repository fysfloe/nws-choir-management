<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
    {{ Form::label('name', __('Name'), ['class' => 'control-label']) }}
    <input type="text" class="form-control" name="name" id="name" value="{{ old('name') ? old('name') : (isset($semester) ? $semester->name : '') }}">

    @if ($errors->has('name'))
        <span class="help-block text-danger">
            <strong>{{ $errors->first('name') }}</strong>
        </span>
    @endif
</div><!-- .form-group -->

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

<div class="form-group{{ $errors->has('deadline') ? ' has-error' : '' }}">
    {{ Form::label('deadline', __('Deadline'), ['class' => 'control-label']) }}
    <input type="datetime-local" class="form-control" name="deadline" id="deadline"
           value="{{ old('deadline') ? old('deadline') : (isset($semester) && $semester->deadline ? (new \DateTime($semester->deadline))->format('Y-m-d\TH:i') : '') }}">

    @if ($errors->has('deadline'))
        <span class="help-block text-danger">
            <strong>{{ $errors->first('deadline') }}</strong>
        </span>
    @endif
</div><!-- .form-group -->

<div class="form-group">
    {{ Form::button(__('Save Semester'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
</div>
