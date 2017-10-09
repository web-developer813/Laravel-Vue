import Vue from 'vue'
import VueResource from 'vue-resource'
import _ from 'lodash';
import moment from 'moment';
import pluralize from 'pluralize';
import helpers from '../mixins/common-helpers.js';
require('../../es/Plugin/responsive-tabs.js');
require('../../es/Plugin/tabs.js');

Vue.use(VueResource);

Vue.component('connections', {
	template: '#connections-template',

	props: ['resource-url', 'no-results', 'follow-url'],

	data: function () {
		return {
			items: [],
			meta: {},
			loading: true,
			nextPageUrl: '',
			active: false,
			filters: {
				search: '',
				filter: '',
				order: '',
			},
			tabs: [
				{id: 'all', name: 'All'},
				{id: 'following', name: 'Following'},
				{id: 'groups', name: 'Groups'}
			]
		}
	},

	mixins: [helpers],
	
	components: {
		tab: {
			props: ['tab', 'filters'],
			data: function() {
				return {
					active: ''
				}
			},
			methods: {
				activate: function() {
					this.active = this.tab.id
					this.$emit('activate',this.tab.id)
				}
			}
		}
	},

	watch: {
		filters: {
			handler: function (val, oldVal) {
				this.loadItems(true)
			},
			deep: true
		}
	},

	created: function () {
		// set categories

		this.loadItems(true);
	},

	methods: {
		loadItems: function (reload) {
			this.loading = true;
			var url = (reload) ? this.resourceUrl : this.nextPageUrl;
			var params = this.filterParams(reload);

			if (reload) this.items = []

			this.$http.get(url, {params: params}).then(function (response) {
				var data = response.data
				this.items = (reload) ? data.items : this.items.concat(data.items)
				if (data.meta) { this.meta = data.meta }
				this.nextPageUrl = data.nextPageUrl
				this.loading = false
			}.bind(this));
		},
		loadMore: function () {
			this.loadItems()
		},
		handleFollow: function(item,user) {
			if (item.following && item.following.followable_id > 0) {
				// I'm following this user already so let's unfollow them
				this.$http.delete(this.followUrl+'/'+item.following.id).then(response => {
					if (response.data.success) {
						item.following = null;
						this.notify('User successfully unfollowed.','Success','success');
					} else {
						this.handleError(response);
					}

				}, response => {
					this.handleError(response);
				});

			} else if (item.following == null) {
				// I'm not following this user so lets follow them
				item.following = true;

				var data = {entity_to_follow: item.volunteer.id, entity_type: 'App\\Volunteer'};

				this.$http.post(this.followUrl,data).then(response => {
					if (response.data.success) {
						this.following = response.data.object;
						this.notify('Successfully Followed User','Success','success');
					} else {
						this.handleError(response);
					}
				}, response => {
					this.handleError(response);
				});
			}
		},
		updateSearchQuery: _.debounce(function (event) {
				this.filters.search = event.target.value
			}, 500, { maxWait: 1000 }
		),
		filterParams: function (reload) {
			if (reload)
			{
				var filters = _.extend({ 'list-group-item': this.feedItem }, this.filters);
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
		activateFilter: function(activeTab) {
			this.filters.filter = activeTab;
		},
		handleError: function (response) {
			this.notify(response,'Error','error');
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
		}
	},
})