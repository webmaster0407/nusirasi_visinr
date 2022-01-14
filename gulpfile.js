const elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');

//elixir.config.sourcemaps = false;

//elixir(mix => {
elixir(function(mix) {
    mix.sass(['app.scss'], 'public/css')
        .webpack('app.js')
        .version(['css/app.css', 'js/app.js']);
});