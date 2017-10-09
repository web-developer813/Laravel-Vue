var Vue = require('vue');
var VueResource = require('vue-resource');
var _ = require('lodash');
var moment = require('moment');
var Notification = require('./notification.js');

Vue.use(VueResource);

Vue.component('verify-hours', {
	props: ['resource-url', 'no-results', 'submit-url', 'opportunity'],

	data: function () {
		return {
			items: [],
			loading: true,
			nextPageUrl: '',
			selection: [],
			allVolunteerIds: [],
			searchOpened: false,
			search: '',
			startDate: '',
			endDate: '',
			hours: 0,
			minutes: 0,
			submitting: false,
		}
	},

	computed: {
		multipleDates: function () {
			// return (this.startDate != this.endDate) ? true : false;
			return (this.startDate && this.endDate && (this.startDate != this.endDate)) ? true : false;
		},
		submitData: function () {
			return {
				opportunity: this.opportunity,
				startDate: this.startDate,
				endDate: this.endDate,
				hours: this.hours,
				minutes: this.minutes,
				volunteers: this.selection,
			}
		},
		startDateLabel: function () {
			return moment(this.startDate).format("MMM Do, YYYY")
		},
		endDateLabel: function () {
			return moment(this.endDate).format("MMM Do, YYYY")
		}
	},

	watch: {
		search: function () {
			this.loadItems(true)
		}
	},

	created: function () {
		this.loadItems()
	},

	methods: {
		updateSearchQuery: _.debounce(function (event) {
				this.search = event.target.value
			}, 500, { maxWait: 1000 }
		),
		loadItems: function (submit) {
			this.loading = true

			var reload = (!this.nextPageUrl || submit == true)
			var url = (reload) ? this.resourceUrl : this.nextPageUrl;
			var params = {'search': this.search };

			this.$http.get(url, {params: params}).then(function (response) {
				var data = response.data
				this.items = (submit) ? data.items : this.items.concat(data.items)
				this.allVolunteerIds = data.allVolunteerIds
				this.nextPageUrl = data.nextPageUrl
				this.loading = false
			}.bind(this));
		},
		loadMore: function () {
			this.loadItems()
		},
        isSelected: function (value) {
        	return this.selection.indexOf(value) > -1 ? true : false;
        },
        toggleVolunteer: function (value) {
			var index = this.selection.indexOf(value);
			if (index === -1)
				this.selection.push(value);
			else
				this.selection.splice(index,1);
        },
        selectAll: function () {
			this.selection = this.allVolunteerIds
        },
        toggleSearch: function (event) {
			this.searchOpened = !this.searchOpened
			Vue.nextTick(function () {
				$('.js-search-input').focus()
			});
        },
        submitHours: function () {
        	this.submitting = true
        	this.$http.post(this.submitUrl, this.submitData).then(function (response) {
        		// success message
        		$('#body').append('<global-notification type="success">' + response.data.message + '</global-notification>')
        		vm.$compile(vm.$el)

        		// reset form
        		this.submitting = false
        		this.hours = 0
        		this.minutes = 0
        		this.selection = []
			}, function (response) {
				// error message
        		var message = 'Hours could not be verified, please try again'
        		$('#body').append('<global-notification type="error">' + message + '</global-notification>')
        		vm.$compile(vm.$el)

				this.submitting = false
			}.bind(this));
        }
	}
})