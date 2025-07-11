import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue'; // Importa el plugin de Vue

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue({ // Agrega el plugin de Vue
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        tailwindcss(),
    ],
    resolve: { // Agrega esta sección
        alias: {
            'vue': 'vue/dist/vue.esm-bundler.js', // Alias para Vue
            // Puedes agregar otros alias si los necesitas, por ejemplo para @
            // '@': path.resolve(__dirname, './resources/js'),
        },
    },
});
