/**
 * Vite - Rural theme
 *
 * Output:
 *   - resources/css/rural.min.css
 *   - resources/images/header.default.png
 */

import { defineConfig } from 'vite';
import browserslistToEsbuild from 'browserslist-to-esbuild';
import { config, resolveNormalised } from './vite.config.shared';

// Plugins
import postcssAutoprefixer from 'autoprefixer';
import postcssRtl from 'postcss-rtlcss';
import postcssImageInliner from 'postcss-image-inliner';
import { viteStaticCopy } from 'vite-plugin-static-copy';

export default defineConfig({
  build: {
    outDir: resolveNormalised(config.public_dir, 'css'),
    emptyOutDir: true,
    target: browserslistToEsbuild(),
    lib: {
      entry: resolveNormalised(__dirname, 'src/sass/theme.scss'),
      formats: ['es'],
      name: 'webtrees-theme-rural',
      cssFileName: 'rural.min'
    }
  },
  css: {
    postcss: {
      plugins: [
        postcssAutoprefixer(),
        postcssRtl({ safeBothPrefix: true }),
        postcssImageInliner({ maxFileSize: 0 })
      ]
    },
    preprocessorOptions: {
      scss: {
        quietDeps: true,
        silenceDeprecations: ['import']
      }
    }
  },
  resolve: {
    alias: [
      { find: '~images', replacement: config.images_dir },
      { find: '~bootstrap', replacement: config.bootstrap_dir },
      { find: '~webtrees', replacement: config.webtrees_dir },
      { find: '~build', replacement: config.build_dir }
    ]
  },
  plugins: [
    viteStaticCopy({
      targets: [
        {
          src: resolveNormalised(config.images_dir, 'header.png'),
          dest: resolveNormalised(config.public_dir, 'images'),
          rename: { stripBase: true, name: 'header.default.png' },
          preserveTimestamps: true
        }
      ]
    })
  ]
});
