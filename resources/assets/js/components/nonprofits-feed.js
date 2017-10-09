var Vue = require('vue');
var VueResource = require('vue-resource');
var _ = require('lodash');
var pluralize = require('pluralize');

Vue.use(VueResource);

Vue.component('nonprofits-feed', {
	template: '#nonprofits-feed-template',

	props: ['resourceUrl', 'location'],

	data: function () {
		return {
			items: [],
			loading: true,
			nextPageUrl: '',
			opened: false,
			categories: [],
			search: '',
			active: {},
			tabs: [
				{id: 'all', name: 'All'},
				{id: 'following', name: 'Following'},
				{id: 'groups', name: 'Groups'}
			],
		}
	},

	watch: {
		search: function (val, old) {
			this.submitFiltersForm()
		},
		categories: function (val, old) {
			this.submitFiltersForm()
		}
	},

	created: function () {
		this.loadItems()
	},

	methods: {
		submitFiltersForm: function () {
			this.items = []
			this.loadItems(true)
		},
		getFilterParams: function () {
			return _.pickBy({
				search: this.search,
				categories: this.categories
			})
		},
		loadItems: function (submit) {
			this.loading = true

			var reload = (!this.nextPageUrl || submit == true)
			var url = (reload) ? this.resourceUrl : this.nextPageUrl;
			var params = (reload) ? this.getFilterParams() : {};

			this.$http.get(url, {params: params}).then(function (response) {
				var data = response.data
				this.items = (submit) ? data.items : this.items.concat(data.items)
				this.nextPageUrl = data.nextPageUrl
				this.loading = false
			}.bind(this));
		},
		loadMore: function () {
			this.loadItems()
		},
		toggleOptions: function () {
			this.opened = !this.opened
		},
		pluralize: function (str,int) {
			return pluralize(str,int);
		}
	}
})