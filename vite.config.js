import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/views/themes/tallstack/assets/css/app.css',
                'resources/views/themes/tallstack/assets/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
