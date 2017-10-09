import Vue from 'vue'
import VueResource from 'vue-resource'
import _ from 'lodash';
import moment from 'moment';
import pluralize from 'pluralize';
import infiniteScroll from 'vue-infinite-scroll';
import stream from 'getstream';
import imagesloaded from 'imagesloaded';
import helpers from '../mixins/common-helpers.js';

Vue.use(infiniteScroll);
Vue.use(VueResource);

var debug = false;

Vue.component('simple-feed', {
	template: '#simple-feed-template',

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
			windowWidth: 0,
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
		debug ? console.log('created') : '';
		// set categories
		if (this.filterCategories) {
			this.filters.categories = this.filterCategories;
		}
		this.loadItems(true);
		window.addEventListener('resize', this.handleResize);
		this.windowWidth = document.documentElement.clientWidth;
		this.handleResize;
	},

	beforeDestroy: function () {
		window.removeEventListener('resize', this.handleResize);
	},

	methods: {
		connect: function () {
			debug ? console.log('connect') : '';
			var client = stream.connect(this.meta.api_key, null, this.meta.app_id);
			var userFeed = client.feed(this.feedName, this.meta.user_id, this.meta.token);
			userFeed.subscribe(function(data) {
				var total = data.new.length;
				if (total == 1) {
					this.notify('You have ' + total + ' new item in your feed! Refresh this page to check it out.','New Feed Item','info');
				} else if (total > 1) {
					this.notify('There are ' + total + ' new items in your feed! Refresh this page to see the updates!','New Feed Items', 'info');
				}
			}.bind(this)).then(function() {
				//Success Handler
				debug ? console.log('Connected to Stream and Listening For Updates!') : '';
			}, function(data) {
				// Error Handler
				debug ? console.log('Error connecting to Stream. Realtime updates disabled.') : '';
			});
		},
		loadItems: function (reload) {
			debug ? console.log('loadItems') : '';
			this.loading = true;
			var url = (reload) ? this.resourceUrl : this.nextPageUrl;
			var params = this.filterParams(reload);

			if (reload) this.items = []

			this.$http.get(url, {params: params}).then(function (response) {
				var data = response.data;
				this.items = (reload) ? data.items : this.items.concat(data.items);
				if (data.meta) { this.meta = data.meta };
				// Fetch updates in realtime
				if (this.feedType == 'realtime') {
					this.connect();
				}
				this.initMasonry();
				this.nextPageUrl = data.nextPageUrl;
				this.loading = false;
			}.bind(this));
		},
		loadMore: function () {
			debug ? console.log('loadmore') : '';
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
		handleResize: function(event) {
			debug ? console.log('handleResize') : '';
			if (this.windowWidth != document.documentElement.clientWidth) {
				this.executeResize;
			}
		},
		executeResize: _.debounce(function (event) {
			debug ? console.log('executeResize') : '';
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
		pluralize: function(str, int) {
			return pluralize(str, int);
		},
		notify: function (message,title,type,options) {
			var title = title ? title : '';
			var type = type ? type : 'info';
			var options = options ? options : {
				"positionClass": "toast-top-full-width",
				"closeButton": true,
				"showDuration": "800",
			};
			toastr[type](message,title,options);
		}
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