const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss')


mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
    .options({
        processCssUrls: false,
        postCss: [ tailwindcss('./tailwind.config.js') ],
    })
mix.browserSync({
    // use existing Nginx virtual host
    proxy: {
        target: '172.31.0.3'
    },
    open: false,
    // Disable it because I use browsersync's default port
    // port: 7777,
});
