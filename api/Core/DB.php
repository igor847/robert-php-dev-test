<?php

namespace Api\Core;

use PDO;

use function Api\Helpers\env;

class DB
{
    private static ?PDO $instance = null;
    private $connection;

    public static function get(): PDO
    {
        if (!self::$instance) {
            $params = [
                'host' => env('DB_HOST'),
                'port' => env('DB_PORT'),
                'dbname' => env('DB_NAME'),
                'charset' => 'utf8mb4',
            ];

            self::$instance = new PDO(
                "mysql:host={$params['host']};port={$params['port']};dbname={$params['dbname']};charset={$params['charset']}",
                env('DB_USER'),
                env('DB_PASS'),
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]
            );
        }
        return self::$instance;
    }
}
