var Vue = require('vue');
var VueResource = require('vue-resource');
import { BasicSelect } from 'vue-search-select'
var _ = require('lodash');
var autosize = require('autosize');
import helpers from '../mixins/common-helpers.js';


Vue.use(VueResource);

Vue.component('incentive-editor', {
	props: {
        submitUrl: {
            type: String,
            required: true,
        },
        title: {
            type: String,
        },
        description :{
        	type: String,
		},
        summary :{
            type: String,
        },
        price :{
            type: String,
        },
        quantity :{
            type: String,
        },
        terms :{
            type: String,
        },
        coupon_code :{
            type: String,
        },
        days_to_use :{
            type: String,
        },
        how_to_use :{
            type: String,
        },
        tag : {
            type: String,
        },
        text_tag : {
            type: String,
        },
        case : {
            type: String,
        },
        employee_specific :{
            type: String
		},
		active : {
        	type : String
		}
	},

    mixins: [helpers],
	data: function () {
        console.log(this.active);
		return {
			tab: 'description',
			submitting: false,
			errors: {},

			tab_inputs: {
				'description': ['title', 'description', 'summary', 'photo', 'price', 'quantity','tag'],
				'details': ['barcode', 'days_to_use', 'how_to_use','case','total_number']
			},

			fields: {
				active: this.active,
				title: this.title,
				description: this.description,
                summary: this.summary,
				price: this.price,
				quantity: this.quantity,
				employee_specific: this.employee_specific,
				terms: this.terms,
				coupon_code: this.coupon_code,
				days_to_use: this.days_to_use,
				how_to_use: this.how_to_use,
                tag : {
                    text: this.text_tag,
					value: this.tag
				},
				case : this.case
			},
            options: [{
                text: "Product",
                value: "product"
            }, {
                text: "Service",
                value: "service"
            }, {
                text: "Incentive",
                value: "incentive"
            }, {
                text: "Event",
                value: "event"
            }],
            searchText: '', // If value is falsy, reset searchText & searchItem
            item: {
                value: '',
                text: ''
            }
		}
	},

	computed: {
		hasErrors: function() {
			return Object.keys(this.errors).length ? true : false
		},
	},
    components: {
        BasicSelect
    },
	mounted: function() {
        var self = this;
        $(document).ready(function() {
        	$(window).keydown(function(event){
				if(event.keyCode == 13) {
					event.preventDefault();
					return false;
				}
			});


            $('.switchery').on('click', function() {
            	self.fields.active = !self.fields.active;
			});
		});
        this.initSwitchery();
	},
	methods: {
        onSelect (item) {
            this.fields.tag = item
        },
        reset () {
            this.fields.tag = {}
        },
        selectOption () {
            // select option from parent component
            this.fields.tag = this.options[0]
        },
		changeActive: function() {
        	alert("change");
        	console.log(this.fields.active);
		},
		formData: function () {
            console.log(this.fields.active);
			var form = $('form')[1];
			var formData = new FormData(form);
			$('.js-file-input').each(function () {
				var files = $(this).prop('files')
				var name = $(this).attr('name')
				if (files.length)
					formData.append(name, files[0]);
			})
			formData.append('tag', this.fields.tag.value);
			return formData;
		},
		submitCreateForm: function () {
			this.submitting = true
			this.$http.post(this.submitUrl, this.formData()).then(function (response) {
				// success
        		window.location.href = response.data.redirect_url
        	// error
			}, function (response) {
				this.handleSubmitError(response)
			}.bind(this));
		},
		submitEditForm: function () {
			this.submitting = true

			this.$http.post(this.submitUrl, this.formData()).then(function (response) {
        		// success message
        		// $('#body').append('<global-notification type="success">' + response.data.message + '</global-notification>')

        		// vm.$compile(vm.$el)
                this.notify(response.data.message,'Success','success')

        		// reset form
        		this.submitting = false
        		this.errors = {}
			// error
			}, function (response) {
				this.handleSubmitError(response)
			}.bind(this));
		},
		handleSubmitError: function (response) {
			this.submitting = false

			// error message
			var message = 'Please fix the errors below and try again'
			// $('#body').append('<global-notification type="error">' + message + '</global-notification>')
			// vm.$compile(vm.$el)
            this.notify(message,'Error','error')

			// show errors
			this.errors = response.data

			// switch to correct tab
			this.switchToTab(this.firstErrorTab())
		},
		firstErrorTab: function () {
			if (!_.size(this.errors)) return this.tab

			var errorKeys = _.keys(this.errors)

			for (var tab in this.tab_inputs) {
				if (_.intersection(errorKeys, this.tab_inputs[tab]).length) {
					return tab;
				}
			}

			return this.tab
		},
		switchToTab: function (tab) {
			this.tab = tab
			$('html, body').scrollTop('0');
			setTimeout(function() {
				autosize.update($('textarea'))
			}, 0)
		}
	}
})