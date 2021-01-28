let mix = require('laravel-mix')

mix
  .setPublicPath('dist')
  .js('resources/js/card.js', 'js')
  .styles(['resources/css/card.css'], 'public/css/card.css')
