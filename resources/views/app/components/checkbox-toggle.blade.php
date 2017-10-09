<div class="form-field {{ form_error($field) }}">
	<label class="form__label">{{ $label }}</label>
	<div class="field-wrapper">
		<label class="input--toggle">
			@if (isset($disabled) && $disabled)
				{{ Form::checkbox($field, $value, false, ['disabled' => 'disabled']) }}
			@elseif(isset($v_model))
				{{ Form::checkbox($field, $value, old($field), ['v-model' => $v_model]) }}
			@else
				{{ Form::checkbox($field, $value, old($field)) }}
			@endif
			<div class="toggle__slider"></div>
		</label>
		<span class="error-block">
			{{ $errors->first($field) ?: '' }}
		</span>
	</div>
</div>