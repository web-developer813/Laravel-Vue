@if(isset($v_model))
	<div class="form-field form-group" v-bind:class="{ 'has-error': errors.{{ $v_model }} }">
		@if(isset($label))
			<label class="form__label form-control-label">{!! $label !!}</label>
		@endif
		<div class="field-wrapper field--text">
			{!! $input !!}
			<span class="error-block" v-if="errors.{{ $v_model }}">
				{{ errors.<?= $v_model ?> }}
			</span>
			@if(isset($help_block))
				<span class="help-block">{{ $help_block }}</span>
			@endif
		</div>
	</div>

{{-- blade --}}
@else
	<div class="form-field form-group  {{ form_error($field) }} ">
		@if(isset($label))
			<label class="form__label form-control-label">{!! $label !!}</label>
		@endif
		<div class="field-wrapper field--text">
			{!! $input !!}
			<span class="error-block">
				{{ $errors->first($field) ?: '' }}
			</span>
			@if(isset($help_block))
				<span class="help-block">{{ $help_block }}</span>
			@endif
		</div>
	</div>
@endif

	
