var elixir = require('laravel-elixir');

require('laravel-elixir-vue');

elixir(function(mix) {
    mix.webpack([
        './resources/assets/src/js/scripts.js'
    ], './resources/assets/js/scripts.js');
});