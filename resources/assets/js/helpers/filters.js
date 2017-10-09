var Vue = require('vue');
var moment = require('moment');
var pluralize = require('pluralize');
var numeral = require('numeral');

// minutes to hours
Vue.filter('min-to-hours', function (minutes) {
	// no hours, no minutes
	if (!minutes) return '0 hours'

	var h = Math.floor(minutes / 60)
	var m = minutes - (h * 60)

	// hours and minutes
	if (h > 0 && m > 0)
	{
		h = numeral(h).format('0,0')
		return pluralize('hour', h, true) + ' ' + pluralize('minute', m, true)
	}
	else if (h > 0 && !(m > 0))
	{
		h = numeral(h).format('0,0')
		return pluralize('hour', h, true)
	}
	else if (m > 0 && !(h > 0))
	return pluralize('minute', m, true)
});

// pluralize
Vue.filter('pluralize', function (number, word, include) {
	n = parseInt(number.toString().replace(/[^0-9.]/g, ""))
	return (include)
		? number + ' ' + pluralize(word, n)
		: pluralize(word, n)
});

// number
Vue.filter('number', function (number) {
	return numeral(number).format('0,0')
})

// datestring
Vue.filter('datestring', function (datetime) {
	return moment(datetime).format('MMM Do, YYYY')
})

// moment
Vue.filter('moment', function (datetime, format) {
	return moment(datetime).format(format)
})