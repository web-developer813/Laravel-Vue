var _ = require('lodash')

// import toastr from '../../vendor/toastr/toastr.js';

var toastr = require('../../vendor/toastr/toastr.js');

module.exports = {
	methods: {
		submitAjaxForm: function (event) {
			var el = $(event.target)
			el.find('button:submit').attr('disabled', true).addClass('btn--loading');
			el.find('.btn').attr('disabled', true);
			var data = this.ajaxFormData(el)
			var  self = this;
			console.log(this);
			this.$http.post(el.attr('action'),data).then(function (response) {
				// success redirect
				if (_.has(response.data, 'redirect_url')) {
        			window.location.href = response.data.redirect_url
        			return true;
				}
				else if (_.has(response.data, 'emptyAvailable')) {
                    var message = 'Please fix the errors below and try again'

                    if (_.has(response.data, 'message')) {
                        message = response.data.message
                    }

                    self.notify(message,'Error','error')
                    el.find('button:submit').removeAttr('disabled').removeClass('btn--loading');
                    el.find('.btn').removeAttr('disabled');

					return true;
				}
				else if (_.has(response.data, 'message')) {
					// success message
                    self.notify(response.data.message,'Success','success')
				}

				else {
					var message = "Success";
                    self.notify(message,'Success','success')
				}

				el.find('button:submit').removeAttr('disabled').removeClass('btn--loading');
				el.find('.btn').removeAttr('disabled');
        	// error
			}, function (response) {
				var message = 'Please fix the errors below and try again'

				if (_.has(response.data, 'message')) {
					message = response.data.message
				}

                self.notify(message,'Error','error')
				
				el.find('button:submit').removeAttr('disabled').removeClass('btn--loading');
				el.find('.btn').removeAttr('disabled');
			}.bind(this));
		},
		ajaxFormData: function (form) {
			var formData = new FormData(form[0]);
			if ($('.js-file-input').length) {
				var files = $('.js-file-input').prop('files')
				if(files.length)
				{
					formData.append('photo', files[0]);
				}
			}
			return formData;
		},
        notify: function (message,title,type,options) {
            var title = title ? title : '';
            var type = type ? type : 'info';
            var options = options ? options : {
                "positionClass": "toast-top-right",
                "closeButton": true,
                "showDuration": "800",
            };
            toastr[type](message,title,options);
        },
	}
}