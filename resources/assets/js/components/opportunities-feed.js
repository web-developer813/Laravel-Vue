var Vue = require('vue');
var VueResource = require('vue-resource');
var _ = require('lodash');
import helpers from '../mixins/common-helpers.js';

Vue.use(VueResource);

Vue.component('opportunities-feed', {
	template: '#opportunities-feed-template',

	props: ['resourceUrl', 'search', 'location', 'feedItem'],

	mixins: [helpers],

	data: function () {
		return {
			items: [],
			loading: true,
			nextPageUrl: '',
			opened: false,
			categories: [],
			virtual: false,
			flexible: false,
		}
	},

	watch: {
		search: function (val, old) {
			this.submitFiltersForm()
		},
		categories: function (val, old) {
			this.submitFiltersForm()
		},
		virtual: function (val, old) {
			this.submitFiltersForm()
		},
		flexible: function (val, old) {
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
		getParams: function (reload) {
			// default params
			var params = {
				'feed-item': this.feedItem
			}

			// return if truthy values
			if (reload)
			{
				var filterParams = _.pickBy({
					search: this.search,
					categories: this.categories,
					virtual: this.virtual,
					flexible: this.flexible
				})
				params = _.merge(params, filterParams)
			}

			return params;
		},
		loadItems: function (submit) {
			this.loading = true

			var reload = (!this.nextPageUrl || submit == true)
			var url = (reload) ? this.resourceUrl : this.nextPageUrl;
			var params = this.getParams(reload)

			this.$http.get(url, {params: params}).then(function (response) {
				var data = response.data
				this.items = (submit) ? data.items : this.items.concat(data.items)
				this.initMasonry({
					itemSelector: '.feed-item',
					percentPosition: true,
					gutter: 24
				});
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

// new Vue({
// 	// el:'#opportunities-index',
// 	el:'body',
// })