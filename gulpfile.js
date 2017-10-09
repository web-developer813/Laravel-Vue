var elixir = require('laravel-elixir');
require('laravel-elixir-browserify-official');

var scssDir = './resources/assets/sass/';

elixir.config.js.browserify.watchify = {
    enabled: true,
    options: {
        poll: true
    }
}

elixir(function(mix) {
	mix.sass(scssDir + 'app.scss');
	mix.sass(scssDir + 'coupon.scss');
	mix.browserify('app.js');
});