var Vue = require('vue');

// Vue.component('global-notification', {
module.exports = {
	template: '#notification-template',

	props: {
		show: false,
		type: { default: 'success' }
	},

	created: function () {
		setTimeout(
			function () {
				this.show = false
			}.bind(this),
			5000
		)
	},

	computed: {
		class: function() {
			var classes = ['alert', 'alert-dismissible'];
			switch (type) {
				case 'error':
					classes.push('alert-danger');
					break;
				case 'warn':
					classes.push('alert-warning');
					break;
				case 'info':
					classes.push('alert-info');
					break;
				case 'primary':
					classes.push('alert-primary');
					break;
				case 'success':
				default:
					classes.push('alert-success');
			}
			return classes.join();
		}
	},
}
// })