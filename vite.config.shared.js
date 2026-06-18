/**
 * Vite - Shared config
 */

import path from 'path';
import { normalizePath } from 'vite';

/**
 * Resolve the provided paths to an absolute path, and normalise it.
 *
 * @param  {...string} paths A sequence of paths or paths segments
 * @returns {string} Normalised absolute path
 */
export const resolveNormalised = (...paths) => normalizePath(path.resolve(...paths));

export const config = {
  images_dir: resolveNormalised(__dirname, 'src/sass/resources/images'),
  webtrees_dir: resolveNormalised(__dirname, 'vendor/fisharebest/webtrees/resources/css'),
  bootstrap_dir: resolveNormalised(__dirname, 'node_modules/bootstrap'),
  build_dir: resolveNormalised(__dirname, 'build'),
  public_dir: resolveNormalised(__dirname, 'resources')
};

export default config;
