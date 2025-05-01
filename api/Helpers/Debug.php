<?php

namespace Api\Helpers;

function dd(...$args): void
{
    echo '<pre>';
    foreach ($args as $arg) {
        print_r($arg);
    }
    echo '</pre>';
    exit;
}
