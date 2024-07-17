/*
  @license
	Rollup.js v4.18.1
	Mon, 08 Jul 2024 15:24:39 GMT - commit 21f9a4949358b60801c948cd4777d7a39d9e6de0

	https://github.com/rollup/rollup

	Released under the MIT License.
*/
export { version as VERSION, defineConfig, rollup, watch } from './shared/node-entry.js';
import './shared/parseAst.js';
import '../native.js';
import 'node:path';
import 'path';
import 'node:process';
import 'node:perf_hooks';
import 'node:fs/promises';
import 'tty';
