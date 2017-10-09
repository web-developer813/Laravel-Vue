var _ = require('lodash');

module.exports = {
	methods: {
		ajaxLink: function (event) {
			var el = $(event.target)
	        var data = {}
			
	        // // prevent double post
	        el.attr('disabled', true).addClass('btn--loading');

	        // // confirm
	        if (el.data('confirm-message'))
	        {
	            var r = confirm(el.data('confirm-message'));
	            if (r == false) {
	                el.removeAttr('disabled');
	                return false;
	            }
	        }

	        var method = el.data('method');
	        if (!method) { method = 'post'; }

			this.$http[method](el.data('url'),data).then(function (response) {
				// success redirect
				if (_.has(response.data, 'redirect_url')) {
					window.location.href = response.data.redirect_url
					return true;
				}

				else if (_.has(response.data, 'message')) {
					// success message
		    		$('#body').append('<global-notification type="success">' + response.data.message + '</global-notification>')
		    		vm.$compile(vm.$el)
				}

				else {
		    		$('#body').append('<global-notification type="success">Success</global-notification>')
		    		vm.$compile(vm.$el)
				}
				
				el.removeAttr('disabled').removeClass('btn--loading');
			// error
			}, function (response) {
				var message = 'Please fix the errors below and try again'

				if (_.has(response.data, 'message')) {
					message = response.data.message
				}
				
				$('#body').append('<global-notification type="error">' + message + '</global-notification>')
				vm.$compile(vm.$el)
				
				el.removeAttr('disabled').removeClass('btn--loading');
			}.bind(this));
		}
	}
}