<div class="form-group{{ $errors->has('comment') ? ' has-error' : '' }}">
    {{ Form::label('comment', __('Comment'), ['class' => 'control-label']) }}
    {{ Form::textarea('comment', old('comment'), ['class' => 'form-control']) }}

    @if ($errors->has('comment'))
        <span class="help-block text-danger">
            <strong>{{ $errors->first('comment') }}</strong>
        </span>
    @endif
</div>

<div class="form-group{{ $errors->has('private') ? ' has-error' : '' }}">
    <label class="control-label">
        {{ Form::checkbox('private', 1, old('private') ? old('private') : 1) }}
        {{ __('Private (only admins will see your comment)') }}
    </label>
</div>

<div class="form-group">
    {{ Form::button(__('Post Comment'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
</div>
