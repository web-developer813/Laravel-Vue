<?php $unique_id = str_random(5); ?>

<section class="flex-columns">
	<div class="radio radio--block column column--grow-1">
		<input
			type="radio"
			name="plan_id"
			id="{{ $unique_id }}_monthly"
			value="volunteer_monthly"
			@if(!empty($vmodel)) v-model="formData.plan_id" @endif
			@if(!isset($default_plan) || $default_plan == 'volunteer_monthly') checked="checked" @endif>
		<label class="radio-label-container" for="{{ $unique_id }}_monthly">
			<div class="radio-input">
				<i class="fa fa-fw fa-circle-o radio-unchecked" aria-hidden="true"></i>
				<i class="fa fa-fw fa-dot-circle-o radio-checked" aria-hidden="true"></i>
			</div>
			<span class="radio-label">
				<strong>$1.30 <span class="small">/ month</span></strong>
				<br><small>Cancel at any time</small>
			</span>
		</label>

	</div>

	<div class="radio radio--block column column--grow-1">
		<input
			type="radio"
			name="plan_id"
			id="{{ $unique_id }}_yearly"
			value="volunteer_yearly"
			@if(!empty($vmodel)) v-model="formData.plan_id" @endif
			@if(isset($default_plan) && $default_plan == 'volunteer_yearly') checked="checked" @endif>
		<label class="radio-label-container" class="radio-label" for="{{ $unique_id }}_yearly">
			<div class="radio-input">
				<i class="fa fa-fw fa-circle-o radio-unchecked" aria-hidden="true"></i>
				<i class="fa fa-fw fa-dot-circle-o radio-checked" aria-hidden="true"></i>
			</div>
			<span class="radio-label">
				<strong>$12 <span class="small">/ year</span></strong>
				<br><small>Save 23% vs. monthly</small>
			</span>
		</label>
	</div>
</section>