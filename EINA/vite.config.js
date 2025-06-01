import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/panell.js',
                'resources/js/chat.js',
                'resources/js/contextes.js',
                'resources/js/nav.js',
            ],
            refresh: true,
        }),
    ],
});
