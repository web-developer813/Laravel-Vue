import Vue from 'vue';
var moment = require('moment');
require('bootstrap-daterangepicker');

Vue.component('date-range-picker', {
	props:['id','start-date-label','end-date-label','multiple-dates'],
	template: '<input type="text" :id="id" :name="id" />',
	mounted: function(){
  	var self = this;
  	var input = $('#'+ this.id);
  	input.daterangepicker({
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
  	});
    input.on('apply.daterangepicker', function(ev, picker) {
    	var formatted = {
    		startDate: picker.startDate.format("YYYY-MM-DD"),
    		endDate: picker.endDate.format("YYYY-MM-DD"),
    	}
    	self.$emit('daterangechanged',formatted);
    });
  } 
});