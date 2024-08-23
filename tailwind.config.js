import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import preset from './vendor/filament/support/tailwind.config.preset';
import fs from 'fs';
import path from 'path';

const themeFilePath = path.resolve(__dirname, 'theme.json');
const activeTheme = fs.existsSync(themeFilePath) ? JSON.parse(fs.readFileSync(themeFilePath, 'utf8')).name : 'anchor';

/** @type {import('tailwindcss').Config} */
export default {
    presets: [preset],
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
        './app/Http/Middleware/WaveEditTab.php',
        './resources/themes/' + activeTheme + '/**/*.blade.php'
    ],

    theme: {
        extend: {
        },
    },

    plugins: [forms, require('@tailwindcss/typography')],
};
