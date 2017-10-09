<section>
	<div class="row">
		<div class="col-md-5">
			<h3 class="mb-30 green-500">Description</h3>
		</div>
		<div class="col-md-7 text-right">
			@include('forprofits.incentives.create._sidebar')
		</div>
	</div>
</section>

<section>
	@include('app.components.form-field', [
		'field' => 'title', 'label' => 'Title', 'v_model' => 'title',
		'input' => Form::text('title', old( $incentive->title ), ['class' => 'form__field form-control', 'v-model' => 'fields.title'])
	])
	@include('app.components.form-field', [
		'field' => 'description', 'label' => 'Description', 'v_model' => 'description',
		'input' => Form::textarea('description', old( $incentive->description ), ['class' => 'form__field form-control', 'rows' => 3, 'v-model' => 'fields.description'])
	])
	@include('app.components.form-field', [
		'field' => 'summary', 'label' => 'Offer Summary', 'v_model' => 'summary',
		'input' => Form::textarea('summary', old( $incentive->summary ), ['class' => 'form__field form-control', 'rows' => 3, 'maxlength' => 300, 'v-model' => 'fields.summary'])
	])

	<div class="form-field" v-bind:class="{ 'has-error': errors.photo }">
		<label class="form__label">Photo</label>
		<div class="field-wrapper">
			<div class="image-upload js-image-uploader" data-name="photo" style="background-image:url({{ $incentive->image  }})"></div>
			<span class="error-block" v-if="errors.photo">
				@{{ errors.photo[0] }}
			</span>
		</div>
	</div>
</section>

<section style="margin-bottom: 40px">
	<div class="form-group" v-bind:class="{ 'has-error': errors.tag }">
		<label for="select2" class="control-label">
			Tags
		</label>
		<basic-select :options="options"
					  :selected-option="fields.tag"
					  placeholder="select Tag"
					  name="tag"
					  @select="onSelect">
		</basic-select>
		<span class="error-block" v-if="errors.tag">
			@{{ errors.tag }}
		</span>
	</div>
	<div class="row">
		<div class="col-md-6 col-sm-6">
			@include('app.components.form-field', [
                'field' => 'quantity', 'label' => 'Quantity', 'v_model' => 'quantity',
                'input' => Form::number('quantity', old( $quantity ), ['class' => 'form__field form-control', 'v-model' => 'fields.quantity'])
            ])
		</div>
		<div class="col-md-6 col-sm-6">
			<div class="form-field form-group" v-bind:class="{ 'has-error': errors.case }">
				<label class="form__label form-control-label">Case</label>
				<div class="field-wrapper field--text">
					<select class="form-control" v-model="fields.case" name="case">
						<option value="">How often</option>
						<option value="daily">Per Day</option>
						<option value="weekly">Per Week</option>
						<option value="monthly">Per Month</option>
						<option value="yearly">Per Year</option>
						<option value="flat">Flat</option>
					</select>
					<span class="error-block" v-if="errors.case">
				@{{ errors.case }}
			</span>
				</div>
			</div>
		</div>
	</div>
	@include('app.components.form-field', [
		'field' => 'price', 'label' => 'Price (In Points)', 'v_model' => 'price',
		'input' => Form::number('price', old( $incentive->price ), ['class' => 'form__field form-control', 'v-model' => 'fields.price'])
	])

	<div class="checkbox-custom checkbox-default">
		<input type="checkbox" id="inputInlineRemember" name="employee_specific" v-model="fields.employee_specific" value="1">
		<label for="inputInlineRemember">Employee Specific</label>
	</div>
</section>
<section>
	<div class="row">
		<div class="col-md-12">
			<h3 class="mb-30 green-500">Details</h3>
		</div>
	</div>
</section>

<section>
	<div class="form-field" v-bind:class="{ 'has-error': errors.barcode }">
		<label class="form__label">Barcode</label>
		<div class="field-wrapper">
			<div class="image-upload js-image-uploader" data-name="barcode" style="background-image:url({{ $incentive->barcode_url  }})"></div>
			<span class="error-block" v-if="errors.barcode">
				@{{ errors.barcode[0] }}
			</span>
		</div>
	</div>
	@include('app.components.form-field', [
		'field' => 'coupon_code', 'label' => 'Coupon Code', 'v_model' => 'coupon_code',
		'input' => Form::text('coupon_code', old('coupon_code'), ['class' => 'form__field form-control', 'v-model' => 'fields.coupon_code'])
	])
	@include('app.components.form-field', [
		'field' => 'days_to_use', 'label' => 'Days to use', 'v_model' => 'days_to_use',
		'input' => Form::text('days_to_use', old('days_to_use'), ['class' => 'form__field form-control', 'v-model' => 'fields.days_to_use'])
	])
	@include('app.components.form-field', [
		'field' => 'how_to_use', 'label' => 'How To Use', 'v_model' => 'how_to_use',
		'input' => Form::textarea('how_to_use', old('how_to_use'), ['class' => 'form__field form-control', 'rows' => 3, 'v-model' => 'fields.how_to_use'])
	])
	@include('app.components.form-field', [
		'field' => 'terms', 'label' => 'Terms & Conditions', 'v_model' => 'terms',
		'input' => Form::textarea('terms', old('terms'), ['class' => 'form__field form-control', 'rows' => 3, 'v-model' => 'fields.terms'])
	])
</section>