<div class="row">
    <div class="col">
        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
            {{ Form::label('title', __('Project title'), ['class' => 'control-label']) }}
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

        <div class="form-group{{ $errors->has('semester_id') ? ' has-error' : '' }}">
            {{ Form::label('semester_id', __('Semester'), ['class' => 'control-label']) }}
            {{ Form::select('semester_id', $semesters, old('semester_id') ? old('semester_id') : ($app->request->get('semester') ? $app->request->get('semester') : null), ['class' => 'form-control']) }}

            @if ($errors->has('semester_id'))
                <span class="help-block text-danger">
                    <strong>{{ $errors->first('semester_id') }}</strong>
                </span>
            @endif
        </div><!-- .form-group -->

        <div class="form-group{{ $errors->has('is_main') ? ' has-error' : '' }}">
            <input type="checkbox" class="form-check-input" name="is_main" id="is_main" @if ((isset($project) && $project->is_main) || old('is_main')) checked @endif>
            {{ Form::label('is_main', __('This is a main project in the semester.'), ['class' => 'form-check-label']) }}
            &nbsp;<span class="oi oi-info" data-toggle="tooltip" title="{{ __('People that attend the semester will automatically participate in this project as well.') }}"></span>
    
            @if ($errors->has('is_main'))
                <span class="help-block text-danger">
                    <strong>{{ $errors->first('is_main') }}</strong>
                </span>
            @endif
        </div><!-- .form-group -->
    </div><!-- .col -->

    <div class="col side-box">
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
    {{ Form::button(__('Save Project'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
</div>
