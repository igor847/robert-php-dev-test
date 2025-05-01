<?php

namespace Api\Helpers;

function envParams()
{
    $env = parse_ini_file(__DIR__ . '/../../.env');
    if ($env === false) {
        throw new \Exception('Could not read .env file');
    }
    return $env;
}

function env(
    string $key,
    string $default = null
) {
    $env = envParams();
    if ($key === null) {
        return $env;
    }
    return $env[$key] ?? $default;
}
