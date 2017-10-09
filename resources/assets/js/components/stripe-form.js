var Vue = require('vue');
var VueResource = require('vue-resource');
var _ = require('lodash');
import helpers from '../mixins/common-helpers.js';

Vue.use(VueResource);

Vue.component('stripe-form', {
	props: ['submit-url'],
	
	mixins: [helpers],

	data: function () {
		return {
			submitting: false,
			formData: {
				plan_id: '',
				name_on_card: '',
				stripeToken: ''
			},
			errors: {
				name_on_card: false,
				card_number: false,
				'card_exp_month': false,
				'card_exp_year': false,
				'card_cvc': false,
			}
		}
	},

	created: function () {
		$(document).ready(function() {
			//$('.js-card-number').payment('formatCardNumber');
			//$('.js-card-cvc').payment('formatCardCVC');
		});
	},

	methods: {
		submit: function () {
			this.submitting = true
			var form = this.$el.querySelector('form')

			if (!this.validateRequired()) {
				this.submitting = false
				this.displayErrorNotication()
				return false
			}

			Stripe.card.createToken(form, this.handleStripeResponse)
		},
		validateRequired: function () {
			let flag = false
			let _this = this
			// var $this = $(this.$el)
			$(this.$el).find('.js-required').each(function() {
			    if (!$(this).val().length) {
			    	// this.errors
			    	_this.errors[$(this).data('error')] = true
			        // $(this).parents('column').addClass('has-error');
			        flag = true;
			    }
			    else {
			    	_this.errors[$(this).data('error')] = false
			    }
			});

			return !flag
		},
		handleStripeResponse: function (status, stripeResponse) {
			console.log(stripeResponse);
			// error
			if (stripeResponse.error) {
				this.handleStripeError(stripeResponse)
			}
			// success
			else {
				this.formData.stripeToken = stripeResponse.id

				// submit
				this.$http.post(this.submitUrl, this.formData).then(function (response) {
					if (response.data.success == true && typeof response.data.redirect_url === 'string') {
						window.location.href = response.data.redirect_url
					} else {
						this.notify(response.data.message,'Error','error');
						console.log(response);
					}
					
				}, function (response) {
					this.handleSubmitError(response)
				}.bind(this));
			}
		},
		handleStripeError: function (response)
		{
			console.log(response);
			this.submitting = false
			this.displayErrorNotication()
			
			var error_type = response.error.type
			var error_param = response.error.param

			// card number
			if ( error_type =='invalid_request_error' || (error_type == 'card_error' && error_param == 'number') )
				this.errors.card_number = true

			// wrong expiration year
			if (error_type == 'card_error' && error_param == 'exp_year')
				this.errors.card_exp_year = true

			// wrong expiration month
			if (error_type == 'card_error' && error_param == 'exp_month')
				this.errors.card_exp_month = true

			// wrong cvc
			if (error_type == 'card_error' && error_param == 'cvc')
				this.errors.card_cvc = true
		},
		displayErrorNotication: function (message) {
			if (!message) message = 'Please fix the errors below and try again'
			this.notify(message,'Error','error');
			
		},
		handleSubmitError: function (response) {
			this.submitting = false
			var message = (response.data.error_message) ? response.data.error_message : null;
			this.displayErrorNotication(message)
			this.errors = response.data
		},
	}
});