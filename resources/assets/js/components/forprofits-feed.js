var Vue = require('vue');
var VueResource = require('vue-resource');
var _ = require('lodash');

Vue.use(VueResource);

Vue.component('forprofits-feed', {
	template: '#forprofits-feed-template',

	props: ['resourceUrl', 'search', 'categories', 'location'],

	data: function () {
		return {
			items: [],
			loading: true,
			nextPageUrl: '',
			opened: false
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
		}
	}
})