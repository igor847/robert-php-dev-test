<?php

namespace Api\Helpers;

class Request
{
    public static function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function getUri(): string
    {
        return $_SERVER['REQUEST_URI'];
    }

    public static function getContentType(): string
    {
        return $_SERVER['CONTENT_TYPE'] ?? 'application/json';
    }

    public static function getBody(): array
    {
        $body = [];
        $buffer = file_get_contents('php://input');

        if (str_contains(self::getContentType(), 'application/json')) {
            $body = json_decode($buffer, true);
        } else if (str_contains(self::getContentType(), 'application/x-www-form-urlencoded')) {
            parse_str($buffer, $body);
        }

        return $body;
    }
}
