<div class="row">
    <div class="col">
        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
            {{ Form::label('title', __('Concert title'), ['class' => 'control-label']) }}
            {{ Form::text('title', old('title'), ['class' => 'form-control']) }}

            @if ($errors->has('title'))
                <span class="help-block text-danger">
                    <strong>{{ $errors->first('title') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
            {{ Form::label('description', __('Description'), ['class' => 'control-label']) }}
            {{ Form::textarea('description', old('description'), ['class' => 'form-control']) }}

            @if ($errors->has('description'))
                <span class="help-block text-danger">
                    <strong>{{ $errors->first('description') }}</strong>
                </span>
            @endif
        </div><!-- .form-group -->

        <div class="form-group{{ $errors->has('place') ? ' has-error' : '' }}">
            {{ Form::label('place', __('Place'), ['class' => 'control-label']) }}
            {{ Form::text('place', old('place'), ['class' => 'form-control']) }}
    
            @if ($errors->has('place'))
                <span class="help-block text-danger">
                    <strong>{{ $errors->first('place') }}</strong>
                </span>
            @endif
        </div><!-- .form-group -->

        <div class="form-group{{ $errors->has('project_id') ? ' has-error' : '' }}">
            {{ Form::label('project_id', __('Project'), ['class' => 'control-label']) }}
            {{ Form::select('project_id', $projects, old('project_id') ? old('project_id') : ($app->request->get('project') ? $app->request->get('project') : null), ['class' => 'form-control']) }}

            @if ($errors->has('project_id'))
                <span class="help-block text-danger">
                    <strong>{{ $errors->first('project_id') }}</strong>
                </span>
            @endif
        </div><!-- .form-group -->
    </div><!-- .col -->

    <div class="col side-box">
        <h3>
            <span class="oi oi-calendar"></span>
            {{ __('Date') }}
        </h3>

        <div class="form-group{{ $errors->has('date') ? ' has-error' : '' }}">
            {{ Form::label('date', __('Date'), ['class' => 'control-label']) }}
            <input type="date" name="date" id="date" value="{{ old('date') ? old('date') : (isset($concert) ? date_format(date_create($concert->date), 'Y-m-d') : null) }}" class="form-control">

            @if ($errors->has('date'))
                <span class="help-block text-danger">
                    <strong>{{ $errors->first('date') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group{{ $errors->has('start_time') ? ' has-error' : '' }}">
            {{ Form::label('start_time', __('Start Time'), ['class' => 'control-label']) }}
            <input type="time" name="start_time" id="start_time" value="{{ old('start_time') ? old('start_time') : (isset($concert) ? $concert->start_time : null) }}" class="form-control">

            @if ($errors->has('start_time'))
                <span class="help-block text-danger">
                    <strong>{{ $errors->first('start_time') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group{{ $errors->has('end_time') ? ' has-error' : '' }}">
            {{ Form::label('end_time', __('End Time'), ['class' => 'control-label']) }}
            <input type="time" name="end_time" id="end_time" value="{{ old('end_time') ? old('end_time') : (isset($concert) ? $concert->end_time : null) }}" class="form-control">

            @if ($errors->has('end_time'))
                <span class="help-block text-danger">
                    <strong>{{ $errors->first('end_time') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group{{ $errors->has('deadline') ? ' has-error' : '' }}">
            {{ Form::label('deadline', __('Deadline'), ['class' => 'control-label']) }}
            <input type="datetime-local" class="form-control" name="deadline" id="deadline"
                   value="{{ old('deadline') ? old('deadline') : (isset($concert) && $concert->deadline ? (new \DateTime($concert->deadline))->format('Y-m-d\TH:i') : '') }}">

            @if ($errors->has('deadline'))
                <span class="help-block text-danger">
            <strong>{{ $errors->first('deadline') }}</strong>
        </span>
            @endif
        </div><!-- .form-group -->

        <h3>
            <span class="oi oi-pulse"></span>
            {{ __('Voices') }}
        </h3>
        <span class="small">{{ __('Add one or more voices and the number of singers needed.') }}</span>

        <div class="row voice">
            <div class="form-group col-8{{ $errors->has('voices') ? ' has-error' : '' }}">
                {{ Form::label('voices[]', __('Voice'), ['class' => 'control-label']) }}
                {{ Form::select('voices[]', $voices, null, ['class' => 'form-control']) }}
            </div><!-- .form-group -->

            <div class="form-group col-4{{ $errors->has('voiceNumber') ? ' has-error' : '' }}">
                {{ Form::label('voiceNumbers[]', __('Singers needed'), ['class' => 'control-label']) }}
                {{ Form::number('voiceNumbers[]', null, ['class' => 'form-control', 'min' => 1, 'max' => 99, 'placeholder' => __('e.g.') . ' 10']) }}
            </div><!-- .form-group -->
        </div><!-- .row -->

        <a href="#" class="btn-link btn-sm add-voice">
            <span class="oi oi-plus"></span>&nbsp;
            {{ __('Add another voice') }}
        </a>

    </div><!-- .col.side-box -->
</div><!-- .row -->

<div class="form-group">
    {{ Form::button(__('Save Concert'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
</div>
