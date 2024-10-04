import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from 'fs';
import path from 'path';

const themeFilePath = path.resolve(__dirname, 'theme.json');
const activeTheme = fs.existsSync(themeFilePath) ? JSON.parse(fs.readFileSync(themeFilePath, 'utf8')).name : 'anchor';
console.log(`Active theme: ${activeTheme}`);

export default defineConfig({
    plugins: [
        laravel({
            input: [
                `resources/themes/${activeTheme}/assets/css/app.css`,
                `resources/themes/${activeTheme}/assets/js/app.js`,
                'resources/css/filament/admin/theme.css',
            ],
            buildDirectory: `demo/${activeTheme}`,
            refresh: [
                `resources/themes/${activeTheme}/**/*`,
            ],
        }),
    ],
    build: {
        rollupOptions: {
            output: {
                entryFileNames: ({ name }) => {
                    // Use the input name explicitly
                    return `js/${name}.js`;
                },
                assetFileNames: ({ name }) => {
                    if (/\.(css|scss)$/.test(name ?? '')) {
                        return 'css/[name][extname]';
                    }
                    return '[name][extname]';
                },
            },
        },
    },
});
