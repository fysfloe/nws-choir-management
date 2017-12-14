    <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
        {{ Form::label('firstname', trans('First Name'), ['class' => 'control-label']) }}
        {{ Form::text('firstname', old('firstname'), ['class' => 'form-control']) }}

        @if ($errors->has('firstname'))
            <span class="help-block text-danger">
                <strong>{{ $errors->first('firstname') }}</strong>
            </span>
        @endif
    </div>

    <div class="form-group{{ $errors->has('surname') ? ' has-error' : '' }}">
        {{ Form::label('surname', trans('Surname'), ['class' => 'control-label']) }}
        {{ Form::text('surname', old('surname'), ['class' => 'form-control']) }}

        @if ($errors->has('surname'))
            <span class="help-block text-danger">
                <strong>{{ $errors->first('surname') }}</strong>
            </span>
        @endif
    </div>

    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        {{ Form::label('email', trans('E-Mail Address'), ['class' => 'control-label']) }}
        {{ Form::email('email', old('email'), ['class' => 'form-control']) }}

        @if ($errors->has('email'))
            <span class="help-block text-danger">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
    </div>

    <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
        <label for="gender" class="control-label">{{ trans('Gender') }}</label>

        <div class="form-check">
            <label class="form-check-label">
                <input type="radio" class="form-check-input" name="gender" value="f" @if (old('gender') === 'f') {{ 'checked="checked"' }} @endif required>
                {{ trans('female') }}
            </label>
        </div>
        <div class="form-check">
            <label class="form-check-label">
                <input type="radio" class="form-check-input" name="gender" value="m" @if (old('gender') === 'f') {{ 'checked="checked"' }} @endif required>
                {{ trans('male') }}
            </label>
        </div>

        @if ($errors->has('gender'))
            <span class="help-block text-danger">
                <strong>{{ $errors->first('gender') }}</strong>
            </span>
        @endif
    </div>

    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
        {{ Form::label('password', trans('Password'), ['class' => 'control-label']) }}
        {{ Form::password('password', ['class' => 'form-control']) }}

        @if ($errors->has('password'))
            <span class="help-block text-danger">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif
    </div>

    <div class="form-group">
        {{ Form::label('password_confirmation', trans('Confirm Password'), ['class' => 'control-label']) }}
        {{ Form::password('password_confirmation', ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {{ Form::button(trans('Save Concert'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
    </div>
</form>
