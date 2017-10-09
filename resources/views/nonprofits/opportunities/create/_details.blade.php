<section>
	<h3>Location</h3>
	@include('app.components.checkbox-toggle', [
		'field' => 'virtual', 'label' => 'Remote Work', 'value' => true, 'v_model' => 'fields.virtual'
	])

	<div class="form-field-wrapper" v-if="!fields.virtual">
		@include('app.components.form-field', [
			'field' => 'location', 'label' => 'Address', 'v_model' => 'location',
			'input' => Form::text('location', old('location'), ['class' => 'form__field js-location-autocomplete', 'v-model' => 'fields.location', 'v-model' => 'fields.location', 'placeholder' => ''])
		])

		@include('app.components.form-field', [
			'field' => 'location_suite', 'label' => 'Unit / Suite', 'v_model' => 'location_suite',
			'input' => Form::text('location_suite', old('location_suite'), ['class' => 'form__field', 'v-model' => 'fields.location_suite'])
		])
	</div>
</section>

<section>
	<h3>Date & Time</h3>
	@include('app.components.checkbox-toggle', [
		'field' => 'flexible', 'label' => 'Flexible Dates', 'value' => true, 'v_model' => 'fields.flexible'
	])
	<div class="form-field" v-show="!fields.flexible">
		<label class="form__label">Date</label>
		<div class="datepicker js-datepicker-parent">
			<div type="text" class="datepicker-label js-datepicker">
				<span class="startDateLabel js-startDateLabel">@{{ startDateLabel }}</span><span v-show="multipleDates"> - <span class="endDateLabel js-endDateLabel">@{{ endDateLabel }}</span></span>
				<div class="calendar-icon"><i class="fa fa-calendar" aria-hidden="true"></i></div>
			</div>
			<input type="hidden" class="js-startDate" value="{{ $today }}" name="start_date" v-model="fields.startDate" v-if="!fields.flexible">
			<input type="hidden" class="js-endDate" value="{{ $today }}" name="end_date" v-model="fields.endDate" v-if="!fields.flexible">
		</div>
	</div>
	@include('app.components.form-field', [
		'field' => 'hours_estimate', 'label' => 'Time Estimate <small>(in hours)</small>', 'v_model' => 'hours_estimate',
		'input' => Form::number('hours_estimate', old('hours_estimate'), ['class' => 'form__field', 'v-model' => 'fields.hours_estimate'])
	])
</section>

<section>
	<h3>Contact Information</h3>
	@include('app.components.form-field', [
		'field' => 'contact_name', 'label' => 'Name', 'v_model' => 'contact_name',
		'input' => Form::text('contact_name', old('contact_name'), ['class' => 'form__field', 'v-model' => 'fields.contact_name'])
	])
	@include('app.components.form-field', [
		'field' => 'contact_email', 'label' => 'Email', 'v_model' => 'contact_email',
		'input' => Form::email('contact_email', old('contact_email'), ['class' => 'form__field', 'v-model' => 'fields.contact_email'])
	])
	@include('app.components.form-field', [
		'field' => 'contact_phone', 'label' => 'Phone', 'v_model' => 'contact_phone',
		'input' => Form::tel('contact_phone', old('contact_phone'), ['class' => 'form__field', 'v-model' => 'fields.contact_phone'])
	])
</section>