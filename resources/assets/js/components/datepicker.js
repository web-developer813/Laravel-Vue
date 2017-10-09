var moment = require('moment');
require('bootstrap-daterangepicker');

$(document).ready(function() {
	$('.js-datepicker').daterangepicker({
		autoUpdateInput: false,
		startDate: window.startDate,
		endDate: window.endDate,
		locale: {
			format: 'YYYY-MM-DD'
		},
		"buttonClasses": "btn btn-small",
		"applyClass": "btn-success",
		"cancelClass": "btn-default",
		"opens": "center",
	}, function(start, end, label) {
		// hidden inputs
		var startDate = this.element.nextAll('.js-startDate:first')
		var endDate = this.element.nextAll('.js-endDate:first')
		$(startDate).val(start.format("YYYY-MM-DD")).change();
		$(endDate).val(end.format("YYYY-MM-DD")).change();
	});
	$('.js-datepicker').on('apply.daterangepicker', function (event, picker) {
		// hidden inputs
		var startDate = $(this).nextAll('.js-startDate:first')
		var endDate = $(this).nextAll('.js-endDate:first')
		$(startDate).val(picker.startDate.format("YYYY-MM-DD")).change();
		$(endDate).val(picker.endDate.format("YYYY-MM-DD")).change();
	})
});