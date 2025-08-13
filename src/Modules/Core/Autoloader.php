<?php
declare(strict_types=1);

namespace MiniStore\Modules\Core;

final class Autoloader
{
    public static function register(): void
    {
        spl_autoload_register(function (string $class): void {
            $prefix  = 'MiniStore\\';
            $baseDir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR; // /src/

            $len = strlen($prefix);
            if (strncmp($prefix, $class, $len) !== 0) {
                return;
            }

            $relative = substr($class, $len); // e.g. Modules\Products\Product
            $file     = $baseDir . str_replace('\\', DIRECTORY_SEPARATOR, $relative) . '.php';

            if (is_file($file)) {
                require $file;
            }
        });
    }
}
