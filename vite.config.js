import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/views/themes/anchor/assets/css/app.css',
                'resources/views/themes/anchor/assets/js/app.js',
                'resources/css/filament/admin/theme.css',
            ],
            //refresh: true,
        }),
    ],
});
