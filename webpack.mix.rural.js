/**
 * Laravel mix - Rural theme
 * 
 * Output:
 * 		- rural.min.css
 */

let mix = require('laravel-mix');
let config = require('./webpack.mix.config');
require('laravel-mix-clean');

//https://github.com/postcss/autoprefixer
const postcss_autoprefixer = require("autoprefixer")();

//https://github.com/jakob101/postcss-inline-rtl
const postcss_rtl = require("@mjhenkes/postcss-rtl")();

//https://github.com/gridonic/postcss-replace
const postcss_replace = require("postcss-replace")({
    data : {
        webtrees: config.webtrees_dir
    }
});

//https://github.com/bezoerb/postcss-image-inliner
const postcss_image_inliner = require("postcss-image-inliner")({
    assetPaths: [config.images_dir],
    maxFileSize: 0,
});

//https://github.com/postcss/postcss-custom-properties
//Enable CSS variables in IE
const postcss_custom_properties = require("postcss-custom-properties")();
mix
    .setPublicPath(config.public_dir + '/css')
    .alias({
        '~build': config.build_dir
    })
    .sass('src/sass/theme.scss', config.public_dir + '/css/rural.min.css')
    .options({
        processCssUrls: false,
        postCss: [
            postcss_replace,
            postcss_rtl,
            postcss_autoprefixer,
            postcss_image_inliner,
            postcss_custom_properties,
        ]
    })
    .sourceMaps()
    .copy(config.images_dir + '/header.png', config.public_dir + '/images')
    .clean()
    ;
