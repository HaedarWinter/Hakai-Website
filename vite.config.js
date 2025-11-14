import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    
    build: {
        // Enable minification
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true, // Remove console.log in production
                drop_debugger: true,
                pure_funcs: ['console.log', 'console.info', 'console.debug'], // Remove specific console methods
            },
            format: {
                comments: false, // Remove comments
            },
        },
        
        // Code splitting for better caching
        rollupOptions: {
            output: {
                manualChunks: {
                    // Split vendor code for better caching
                    vendor: ['axios'],
                },
            },
        },
        
        // CSS code splitting
        cssCodeSplit: true,
        
        // Generate source maps for debugging (disable in production for smaller files)
        sourcemap: false,
        
        // Chunk size warnings (KB)
        chunkSizeWarningLimit: 1000,
        
        // Optimize dependencies
        commonjsOptions: {
            transformMixedEsModules: true,
        },
        
        // Asset file names with hashing for cache busting
        assetsDir: 'assets',
        assetsInlineLimit: 4096, // Inline assets smaller than 4kb
    },
    
    // Optimize dependencies
    optimizeDeps: {
        include: ['axios'],
        exclude: [],
    },
    
    // Server configuration for development
    server: {
        hmr: {
            host: 'localhost',
        },
    },
});
