var Vue = require('vue');

Vue.component('nonprofit-create', {
	data: function () {
		return {
			tab: 'categories',
			submitting: false,
			errors: {},

			tab_inputs: {
				'categories': ['categories'],
			},

			formData: {
				categories: [],
			}
		}
	},

	methods: {
		toggleCategory: function (category) {
			var categories = this.formData.categories
			var i = categories.indexOf(category);

			if (i === -1)
			{
				if (this.formData.categories.length >= 3)
				{
					$('#body').append('<global-notification type="error">You can only select up to 3 categories</global-notification>')
					vm.$compile(vm.$el)
				}
				else
					categories.push(category);
			}
			else
				categories.splice(i,1);
		}
	}
})