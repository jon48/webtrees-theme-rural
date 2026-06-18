/**
 * Vite - Mainstream webtrees base
 *
 * Output:
 *   - webtrees.base.css
 *   - webtrees.vendor-patches.css
 */

import { defineConfig } from 'vite';
import browserslistToEsbuild from 'browserslist-to-esbuild';
import { config, resolveNormalised } from './vite.config.shared';

// Plugins
import postcssImageInliner from 'postcss-image-inliner';

export default defineConfig({
  build: {
    cssCodeSplit: true,
    outDir: config.build_dir,
    emptyOutDir: true,
    target: browserslistToEsbuild(),
    minify: false,
    lib: {
      entry: [
        resolveNormalised(config.webtrees_dir, '_base.css'),
        resolveNormalised(config.webtrees_dir, '_vendor-patches.css')
      ]
    },
    rollupOptions: {
      output: {
        assetFileNames: (assetInfo) => 'webtrees.' + assetInfo.names[0].replace('_', '')
      }
    }
  },
  css: {
    postcss: {
      plugins: [
        postcssImageInliner({ maxFileSize: 0 })
      ]
    }
  }
});
