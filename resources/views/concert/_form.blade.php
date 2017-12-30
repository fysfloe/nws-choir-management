<div class="row">
    <div class="col">
        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
            {{ Form::label('title', trans('Concert title'), ['class' => 'control-label']) }}
            {{ Form::text('title', old('title'), ['class' => 'form-control']) }}

            @if ($errors->has('title'))
                <span class="help-block text-danger">
                    <strong>{{ $errors->first('title') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
            {{ Form::label('description', trans('Description'), ['class' => 'control-label']) }}
            {{ Form::textarea('description', old('description'), ['class' => 'form-control']) }}

            @if ($errors->has('description'))
                <span class="help-block text-danger">
                    <strong>{{ $errors->first('description') }}</strong>
                </span>
            @endif
        </div><!-- .form-group -->
    </div><!-- .col -->

    <div class="col side-box">
        <h3>
            <span class="oi oi-calendar"></span>
            {{ trans('Dates') }}
        </h3>
        <span class="small">{{ trans('Add one or more dates for the concert.') }}</span>

        <div class="form-group{{ $errors->has('dates') ? ' has-error' : '' }}">
            <input type="datetime-local" class="form-control date" name="dates[]">

            <a href="#" class="btn-link btn-sm add-date">
                <span class="oi oi-plus"></span>&nbsp;
                {{ trans('Add another date') }}
            </a>
        </div><!-- .form-group -->
    </div><!-- .col.side-box -->
</div><!-- .row -->

<div class="form-group">
    {{ Form::button(trans('Save Concert'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
</div>
