/**
 * Laravel mix - Rural theme
 *
 * Output:
 *   - rural.min.css
 */

const mix = require('laravel-mix');
const config = require('./webpack.mix.config');
require('laravel-mix-clean');

// https://github.com/postcss/autoprefixer
const postcssAutoprefixer = require('autoprefixer')();

// https://github.com/elchininet/postcss-rtlcss
const postcssRtl = require('postcss-rtlcss')({ safeBothPrefix: true });

// https://github.com/gridonic/postcss-replace
const postcssReplace = require('postcss-replace')({
  data: {
    webtrees: config.webtrees_dir
  }
});

// https://github.com/bezoerb/postcss-image-inliner
const postcssImageInliner = require('postcss-image-inliner')({
  assetPaths: [config.images_dir],
  maxFileSize: 0
});

// https://github.com/postcss/postcss-custom-properties
// Enable CSS variables in IE
const postcssCustomProperties = require('postcss-custom-properties')();
mix
  .setPublicPath(config.public_dir + '/css')
  .alias({
    '~build': config.build_dir
  })
  .sass('src/sass/theme.scss', config.public_dir + '/css/rural.min.css')
  .options({
    processCssUrls: false,
    postCss: [
      postcssReplace,
      postcssRtl,
      postcssAutoprefixer,
      postcssImageInliner,
      postcssCustomProperties
    ]
  })
  .copy(config.images_dir + '/header.png', config.public_dir + '/images')
  .clean()
;
