<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
    {{ Form::label('title', trans('Concert title'), ['class' => 'control-label']) }}
    {{ Form::text('title', old('title'), ['class' => 'form-control']) }}

    @if ($errors->has('title'))
        <span class="help-block text-danger">
            <strong>{{ $errors->first('title') }}</strong>
        </span>
    @endif
</div>

<h3>{{ trans('Dates') }}</h3>
<span class="small">{{ trans('Add one or more dates for the concert.') }}</span>

<div class="form-group{{ $errors->has('dates') ? ' has-error' : '' }}">
    <input type="datetime-local" class="form-control" name="dates[]">

    <a href="#" class="add-date">{{ trans('Add another date') }}</a>
</div>

<div class="form-group">
    {{ Form::button(trans('Save Concert'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
</div>
