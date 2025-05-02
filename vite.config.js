import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react-swc';
import path from 'path';
import { fileURLToPath } from 'url';
import { viteStaticCopy } from 'vite-plugin-static-copy';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

export default defineConfig({
  root: 'frontend',
  build: {
    outDir: '../public',
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
      input: path.resolve(__dirname, 'frontend/src/main.jsx')
    }
  },
  plugins: [
    react(),
    viteStaticCopy({
      targets: [
        {
          src: 'static/index.php',
          dest: ''
        },
        {
          src: 'static/.htaccess',
          dest: ''
        }
      ]
    })
  ],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'frontend/src')
    }
  }
});
