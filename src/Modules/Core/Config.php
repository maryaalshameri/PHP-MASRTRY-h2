<?php
declare(strict_types=1);

namespace MiniStore\Modules\Core;

final class Config
{
    private static array $data = [];

    public static function load(string $path): void
    {
        self::$data = require $path;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return self::$data[$key] ?? $default;
    }
}
