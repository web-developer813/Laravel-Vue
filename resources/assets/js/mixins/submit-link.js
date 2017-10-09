module.exports = {
	methods: {
		submitLink: function (event) {
			var el = $(event.target)
			
	        // // prevent double post
	        el.attr('disabled', true);

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

	        var form = document.createElement("form");
	        form.setAttribute("method", 'POST');
	        form.setAttribute("action", el.attr('href'));
	        $(form).addClass("hidden");
	        $(form).append('<input type="hidden" name="_method" value="' + method + '">');
	        $(form).append('<input type="hidden" name="_token" value="' + window.token + '">');
	        $(form).appendTo('body').submit();
		},
	}
}