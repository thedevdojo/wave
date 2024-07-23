import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/views/themes/cove/assets/css/app.css',
                'resources/views/themes/cove/assets/js/app.js',
                // 'resources/css/filament/admin/theme.css',
            ],
            //refresh: true,
        }),
    ],
});
