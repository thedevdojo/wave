import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                './css/main.css',
                './js/main.js',
            ],
            refresh: true,
        }),
    ],
    build: {
        outDir: path.resolve(__dirname, '../../../../public/billing'),
        emptyOutDir: true,
        rollupOptions: {
            input: {
                main: path.resolve(__dirname, '/js/main.js'),
                style: path.resolve(__dirname, '/css/main.css'),
            },
            output: {
                entryFileNames: 'main.js',
                chunkFileNames: 'main.js',
                assetFileNames: 'main.[ext]',
                manualChunks: undefined,
            }
        }
    }
});