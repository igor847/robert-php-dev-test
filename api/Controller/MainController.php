<?php

namespace Api\Controller;

use function Api\Helpers\dd;

class MainController
{
    public function index()
    {
        $manifest = json_decode(file_get_contents(PATH_PUBLIC . '/.vite/manifest.json'), true);
        $js = '/' . $manifest['src/main.jsx']['file'];
        $css = $manifest['src/main.jsx']['css'] ?? [];

        ?>
        <!DOCTYPE html>
        <html>
            <head>
                <title>My App</title>
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css">
                <?php
                foreach ($css as $file) {
                    echo '<link rel="stylesheet" href="/' . $file . '">';
                }
                ?>
            </head>
            <body>
                <div id="root"></div>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
                <script type="module" src="<?= $js ?>"></script>
            </body>
        </html>
        <?php
    }
}
