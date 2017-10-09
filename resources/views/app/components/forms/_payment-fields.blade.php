<div class="form-field">
	<div class="flex-columns">

		<div class="column column--50 column--grow-1" v-bind:class="{ 'has-error': errors.name_on_card }">
			<label for="card-name">Name on Card</label>
			<div class="field-wrapper field--text">
				<input
					type="text"
					v-model="formData.name_on_card"
					name="name_on_card"
					data-stripe="name"
					data-error="name_on_card"
					class="js-required">
			</div>
		</div>

		<div class="column column--50 column--grow-1" v-bind:class="{ 'has-error': errors.card_number }">
			<label for="card-number">Card Number</label>
			<div class="field-wrapper field--text">
				<input
					type="text"
					size="20"
					data-stripe="number"
					data-error="card_number"
					value=""
					class="js-card-number js-required">
			</div>
		</div>

	</div>
</div>
<div class="form-field flex-columns">
	<div class="column column--30 column--grow-1" v-bind:class="{ 'has-error': errors.card_exp_month }">
		<label for="">Expiration Month</label>
		@include('app.components.forms.card-month')
	</div>
	<div class="column column--30 column--grow-1" v-bind:class="{ 'has-error': errors.card_exp_year }">
		<label for="">Expiration Year</label>
		@include('app.components.forms.card-year')
	</div>
	<div class="column column--30 column--grow-1" v-bind:class="{ 'has-error': errors.card_cvc }">
		<label for="card-cvc">CVC</label>
		<div class="field-wrapper field--text">
			<input type="text" size="4" data-stripe="cvc" data-error="card_cvc" value="" class="js-card-cvc js-required">
		</div>
	</div>
</div>
