import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import { resolve } from 'path';

export default defineConfig({
    plugins: [vue()],
    build: {
        outDir: 'dist',
        emptyOutDir: true,
        lib: {
            entry: resolve(__dirname, 'resources/js/laravilt-core.js'),
            name: 'LaraviltCore',
            fileName: (format) => `laravilt-core.${format}.js`,
            formats: ['es', 'umd']
        },
        rollupOptions: {
            external: ['vue'],
            output: {
                globals: {
                    vue: 'Vue'
                },
                assetFileNames: 'laravilt-core.[ext]'
            }
        },
        sourcemap: true
    }
});