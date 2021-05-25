/**
 * Laravel mix - Mainstream webtrees base
 *
 * Output:
 *   - webtrees.base.css
 */

const mix = require('laravel-mix');
const config = require('./webpack.mix.config');

// https://github.com/postcss/postcss-import
const postcssImport = require('postcss-import')();

// https://github.com/bezoerb/postcss-image-inliner
const postcssImageInliner = require('postcss-image-inliner')({
  assetPaths: [config.webtrees_dir],
  maxFileSize: 0
});

mix
  .setPublicPath(config.build_dir)
  .postCss(config.webtrees_dir + '/_base.css', config.build_dir + '/webtrees.base.css')
  .postCss(config.webtrees_dir + '/_vendor-patches.css', config.build_dir + '/webtrees.vendor-patches.css')
  .options({
    processCssUrls: false,
    postCss: [
      postcssImport,
      postcssImageInliner
    ]
  });
