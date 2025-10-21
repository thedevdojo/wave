import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import fs from 'fs';
import path from 'path';

const themeFilePath = path.resolve(__dirname, 'theme.json');
const activeTheme = fs.existsSync(themeFilePath) ? JSON.parse(fs.readFileSync(themeFilePath, 'utf8')).name : 'anchor';

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './app/Filament/**/*.php',
    './resources/views/filament/**/*.blade.php',
    './vendor/filament/**/*.blade.php',
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/views/components/**/*.blade.php',
    './resources/views/components/blade.php',
    './wave/resources/views/**/*.blade.php',

    // ensure Tailwind sees classes inside theme blade/js/css files (include all themes + active theme)
    './resources/themes/**/*.{blade.php,html,js,ts,vue,css}',
    `./resources/themes/${activeTheme}/**/*.{blade.php,html,js,ts,vue,css}`,

    // include plugins and config where classes might appear
    './resources/plugins/**/*.{php,blade.php,js}',
    './config/*.php',
  ],

  theme: {
    extend: {
      animation: {
        marquee: 'marquee 25s linear infinite',
      },
      keyframes: {
        marquee: {
          from: { transform: 'translateX(0)' },
          to: { transform: 'translateX(-100%)' },
        },
      },
    },
  },

  // don't mix require() in ESM context — imported plugins above
  plugins: [forms, typography],
};