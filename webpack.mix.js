const { mix } = require('laravel-mix');

mix.sass('resources/assets/sass/app.scss', 'public/css')
	.sass('resources/assets/sass/coupon.scss','public/css')
	.js('resources/assets/js/app.js', 'public/js')
	.js('resources/assets/js/auth.js', 'public/js');