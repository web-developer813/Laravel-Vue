var Vue = require('vue');
var VueResource = require('vue-resource');
var _ = require('lodash');
var moment = require('moment');
import Ladda from 'ladda';
var cloudinary = require('cloudinary-jquery-file-upload');
import dropify from 'dropify';
import helpers from '../mixins/common-helpers.js';
import ToggleButton from 'vue-js-toggle-button'

Vue.use(ToggleButton);
Vue.use(VueResource);
var eventHub = new Vue();

Vue.component('opportunity-editor', {
	props: {
		submitUrl: {
			type: String,
			required: true,
		},
		cloudinaryName: {
			type: String,
			required: true,
		},
		cloudinaryKey: {
			type: String,
			required: true,
		},
		ispublished: {
			type: Boolean,
			default: false
		},
		title: {
			type: String,
		},
		description: {
			type: String,
		},
		maximum_accepted_applicant: {
			type: Number
		},
		image: null,
		selcategories: null,
		isvirtual: {
			type: Boolean,
			default: false
		},
		location: null,
		location_suite: null,
		isflexible: {
			type: Boolean,
			default: false
		},
		hasStartDate: null,
		hasEndDate: null,
		hours_estimate: null,
		contact_name: null,
		contact_email: null,
		contact_phone: null,
	},

	mixins: [helpers],

	data: function () {
		return {
			submitting: false,
			errors: {},
			published: this.ispublished,
			virtual: this.isvirtual,
			flexible: this.isflexible,
			isSaving: false,
			startDate: this.hasStartDate,
			endDate: this.hasEndDate,
			startRange: this.hasStartDate,
			endRange: this.hasEndDate,
			categories: (this.selcategories != null) ? JSON.parse(this.selcategories) : [],
			max_accepted_applicant: this.maximum_accepted_applicant
		}
	},

	mounted() {
		this.initSwitchery();
	},

	watch: {
		virtual: function () {
			if (this.virtual == true)
				this.location = ''
				this.location_suite = ''
		},
	},

	events: {
		MapsApiLoaded: function () {
			
		}
	},

	computed: {
		catCount: function() {
			return this.categories.length;
		},
		multipleDates: function () {
			return (this.startDate && this.endDate && (this.startDate != this.endDate)) ? true : false;
		},
		startDateLabel: function () {
			return (this.startDate) ? moment(this.startDate).format("MMM Do, YYYY") : '';
		},
		endDateLabel: function () {
			return (this.endDate) ? moment(this.endDate).format("MMM Do, YYYY") : '';
		},
		hasErrors: function() {
			return Object.keys(this.errors).length ? true : false;
		},
	},

	methods: {
		formData: function () {
			var form = $('form')[1];
			var formData = new FormData(form);
			if ($('.js-file-input').length) {
				var files = $('.js-file-input').prop('files')
				if(files.length)
				{
					formData.append('photo', files[0]);
				}
			}
			return formData;
		},
		submitCreateForm: function (event) {
			this.submitting = true;
			var $btn = $('.ladda-button:first');
			var l = Ladda.create($btn[0]);
			l.start();
			this.$http.post(this.submitUrl, this.formData()).then(function (response) {
				// success
				l.stop();
        		window.location.href = response.data.redirect_url;

        		// reset form
			}, function (response) {
				l.stop();
				this.handleSubmitError(response)
			}.bind(this));
		},
		submitEditForm: function () {
			this.submitting = true
			var $btn = $('.ladda-button:first');
			var l = Ladda.create($btn[0]);
			l.start();
			this.$http.post(this.submitUrl, this.formData()).then(function (response) {
				l.stop();
        		// success message
        		this.notify(response.data.message,'Success','success')

        		// reset form
        		this.submitting = false
        		this.errors = {}
			}, function (response) {
				l.stop();
				this.handleSubmitError(response)
			}.bind(this));
		},
		handleSubmitError: function (response) {
			this.submitting = false

			// error message
			var message = 'Please fix the errors below and try again'
			this.notify(message,'Error','error')

			// show errors
			this.errors = response.data

		},
		toggleVirtual: function(val) {
			this.virtual = val;
		},
		toggleFlexible: function(val) {
			this.flexible = val;
		},
		imageUpload: function (event,files) {
			console.log(files);
		},
		onDateRangeChanged: function(picker){
			this.startDate = picker.startDate;
			this.endDate = picker.endDate;
		},
	}
})