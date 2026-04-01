import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import vuetify from 'vite-plugin-vuetify';
import tailwindcss from '@tailwindcss/vite';
import vueJsx from '@vitejs/plugin-vue-jsx';
import vueDevTools from 'vite-plugin-vue-devtools';
import { fileURLToPath, URL } from 'node:url';

export default defineConfig({
    resolve: {
        alias: {
            '@': fileURLToPath(new URL('./resources/tenant-admin-portal/src', import.meta.url)),
            '@locales': fileURLToPath(new URL('./resources/tenant-admin-portal/src/locales', import.meta.url)),
        },
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.scss', 
                'resources/js/app.js',
                'resources/tenant-admin-portal/src/main.ts'
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        vueJsx(),
        vueDevTools(),
        vuetify({ autoImport: true }),
        tailwindcss(),
    ],
    server: {
        host: 'localhost',
        port: 5180,
        cors: true,
        allowedHosts: ['.nems.com'],
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
        fs: {
            allow: [
                fileURLToPath(new URL('./', import.meta.url)),
                fileURLToPath(new URL('./resources/tenant-admin-portal', import.meta.url))
            ]
        }
    },
});
