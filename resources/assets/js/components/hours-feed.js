import Vue from 'vue'
import VueResource from 'vue-resource'
import _ from 'lodash';
import moment from 'moment';
import infiniteScroll from 'vue-infinite-scroll';
import helpers from '../mixins/common-helpers.js';

Vue.use(infiniteScroll);
Vue.use(VueResource);

Vue.component('hours-feed', {
	template: '#hours-feed-template',

	props: {
		resourceUrl: {
			type: String,
			required: true
		},
		noResults: null,
		feedItem: null,
	    filterCategories: null,
	    optionToggles: null,
	    feedType: {
	    	type: String,
	    	default: 'standard'
	    },
	    feedName: {
	    	type: String,
	    	default: 'timeline_aggregated'
	    },
		followUrl: null,
		likeUrl: null,
 	},

 	mixins: [helpers],

	data: function () {
		return {
			items: [],
			meta: {},
			loading: true,
			nextPageUrl: '',
			filters: {
				search: '',
				status: '',
			},
		}
	},
	watch: {
		filters: {
			handler: function (val, oldVal) {
				this.loadItems(true);
			},
			deep: true
		}
	},
	created: function () {
		// set categories
		if (this.filterCategories) {
			this.filters.categories = this.filterCategories;
		}
		this.loadItems(true);
		window.addEventListener('resize', this.handleResize);
		this.handleResize();
	},

	beforeDestroy: function () {
		window.removeEventListener('resize', this.handleResize);
	},

	methods: {
		loadItems: function (reload) {
			this.loading = true;
			var url = (reload) ? this.resourceUrl : this.nextPageUrl;
			var params = this.filterParams(reload);

			if (reload) this.items = []

			this.$http.get(url, {params: params}).then(function (response) {
				var data = response.data;
				this.items = (reload) ? data.items : this.items.concat(data.items);
				if (data.meta) { this.meta = data.meta };
				this.initMasonry();
				this.nextPageUrl = data.nextPageUrl;
				this.loading = false;
			}.bind(this));
		},
		loadMore: function () {
			if (this.nextPageUrl) {
				this.loadItems()
			}
		},
		updateSearchQuery: _.debounce(function (event) {
				this.filters.search = event.target.value
			}, 500, { maxWait: 1000 }
		),
		updateItem: function (event) {
			var btn = event.target
			var url = btn.getAttribute('data-update-url')

			// disable button
			btn.setAttribute('disabled', 'true');

			// get data
			var data = (btn.getAttribute('data-update-data'))
				? JSON.parse(btn.getAttribute('data-update-data').replace(/'/g, '"').replace(/([a-z][^:]*)(?=\s*:)/gi, '"$1"'))
				: {};

			this.$http.put(url, data).then(function (response) {
				// success message
        		this.notify(response.data.message,'','success');

        		// update item
        		if (_.has(response.data, 'item'))
        		{
					var item_type = btn.getAttribute('data-item-type')
					var item_id = btn.getAttribute('data-item-id')

	        		var elementPos = this.items.map(function(x) {
	        			return x[item_type].id;
	        		}).indexOf(parseInt(item_id));

	        		Vue.set(this.items, elementPos, response.data.item)
        		}

        		// enable button
        		btn.removeAttribute('disabled')
			}, function (response) {
				// error message
        		handleError(response)
        		// enable button
        		btn.removeAttribute('disabled')
			}.bind(this));
		},
		deleteItem: function (id, event) {
			var btn = event.target;
			var url = btn.getAttribute('data-delete-url');

			// disable button
			btn.setAttribute('disabled', 'true');

			this.$http.delete(url).then(function (response) {
				// success message
        		this.notify(response.data.message,'','success');

        		var item_type = btn.getAttribute('data-item-type');

				this.items = this.items.filter(function( entry ) {
					return entry[item_type].id !== id;
				});
			}, function (response) {
				// error message
				handleError(response);
        		// enable button
        		btn.removeAttribute('disabled');
			}.bind(this));
		},
		handleError: function (data) {
			console.log(data);
		},
		handleResize: _.debounce(function (event) {
			this.initMasonry();
			}, 1000, { maxWait: 2000 }
		),
		filterParams: function (reload) {
			if (reload)
			{
				var filters = _.extend({ 'feed-item': this.feedItem }, this.filters);
				// var filters = {
				// 	'feed-item': this.feedItem,
				// 	search: this.filters.search,
				// 	status: this.filters.status,
				// 	categories: this.filters.categories
				// }
				return _.omitBy(filters, function(value) {
					return _.isUndefined(value) || _.isNull(value) || value === '';
				});
			}

			return _.omitBy({
				'feed-item': this.feedItem
			}, _.isNil)
		},
		toggleValue: function (option) {
			this.optionToggles[option] = !this.optionToggles[option]
		},
	},
	filters: {
		timeago: function (date) {
			return moment(date, 'YYYY-MM-DD hh:mm:ss').fromNow(true)
		},
		datetomonth: function (date) {
			return moment(date, 'YYYY-MM-DD').format('MMM')
		},
		datetoday: function (date) {
			return moment(date, 'YYYY-MM-DD').format('D')
		},
		datetoyear: function (date) {
			return moment(date, 'YYYY-MM-DD').format('YYYY')
		},
	},
})