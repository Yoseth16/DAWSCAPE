import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/audio-manager.js',
                'resources/js/sfx-integration.js',
                'resources/js/validation-sfx-helper.js',
                'resources/css/prueba6.css',
                'resources/js/prueba6.js',
                'resources/css/admin.css',
                'resources/css/maquina-cafe.css'
            ],
            refresh: true,
        }),
    ],
});
