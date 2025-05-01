<?php

namespace Api\Helpers;

const allowMethods = [
    'GET',
    'POST',
    'PUT',
    'DELETE',
    'OPTIONS'
];

function jsonResponse(
    mixed $data,
    int $statusCode = 200,
    array $headers = []
): string {
    $headers = array_merge(
        $headers,
        [
            'Content-Type' => 'application/json',
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => implode(',', allowMethods),
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
        ]
    );
    http_response_code($statusCode);
    foreach ($headers as $key => $value) {
        header("$key: $value", true, $statusCode);
    }
    return json_encode($data);
}
