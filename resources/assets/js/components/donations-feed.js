import Vue from 'vue'
import VueResource from 'vue-resource'
import _ from 'lodash';
import moment from 'moment';
import infiniteScroll from 'vue-infinite-scroll';
import stream from 'getstream';
import helpers from '../mixins/common-helpers.js';

Vue.use(infiniteScroll);
Vue.use(VueResource);

Vue.component('donations-feed', {
	template: '#donations-feed-template',

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
		connect: function () {
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
				console.log('Connected to Stream and Listening For Updates!');
			}, function(data) {
				// Error Handler
				console.log('Error connecting to Stream. Realtime updates disabled.');
			});
		},
		loadItems: function (reload) {
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
		handleLike: function(item,user) {
			if (item.object.liked_by_me == true && item.object.like_id > 0) {
				// Unlike
				item.object.liked_by_me = false;
				item.object.total_likes = parseInt(item.object.total_likes) - 1;

				this.$http.delete(this.likeUrl+'/'+item.object.like_id).then(response => {
					if (response.data.success) {
						item.object.like_id = 0;
						console.log('Object successfully unliked.');
					} else {
						this.notify(response.data.message,'Error','error',{'positionClass': 'toast-top-right'});
					}

				}, response => {
					this.handleError(response);
				});
			} else if (item.object.liked_by_me == false) {
				// Like
				item.object.liked_by_me = true;
				item.object.total_likes = parseInt(item.object.total_likes) + 1;

				var data = {
					object_id: item.object.id,
					object_type: item.foreign_id,
					object_actor: item.actor.id,
				};
				this.$http.post(this.likeUrl,data).then(response => {
					if (response.data.success) {
						item.object.like_id = response.data.object.id;
						console.log('The Object is liked and should persist');
					} else {
						this.notify(response.data.message,'Error','error',{'positionClass': 'toast-top-right'});
					}
				}, response => {
					this.handleError(response);
				});
			}
		},
		likeText: function(item) {
			var text = item.object.total_likes;
			if (item.object.liked_by_me) {
				// I like this
				if (item.object.total_likes > 1) {
					// Me and others like this
					var text = 'Liked by You and ' + (item.object.total_likes - 1) + ' others';
				} else {
					// Only I like this
					var text = 'You Like This';
				}
			}
			return text;
		},
		unFollow: function(follow_id,name) {
			var name = (name != 'null') ? name : 'this user';
			if (follow_id > 0) {
				this.$http.delete('api/follows/'+follow_id).then(response => {
					if (response.data.success) {
						this.notify('Successfully unfollowed ' + name + '. You\'ll no longer see their content in your newsfeed.','User Unfollowed','success', {"positionClass": "toast-top-right"} );
						this.loadItems(true);
					} else {
						this.notify('There was an issue unfollowing ' + name + '. Please try again.', 'Error', 'error', {"positionClass": "toast-top-right"});
						this.handleError(response);
					}
				}, response => {
					this.handleError(response);
				});

			}
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