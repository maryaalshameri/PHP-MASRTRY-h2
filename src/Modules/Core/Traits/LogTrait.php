<?php
declare(strict_types=1);

namespace MiniStore\Modules\Core\Traits;

use MiniStore\Modules\Core\Config;

trait LogTrait
{
    protected function log(string $message): void
    {
        $path = Config::get('LOG_PATH', __DIR__ . '/../../../../storage/logs/app.log');

        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $line = sprintf("[%s] [%s] %s\n", date('Y-m-d H:i:s'), static::class, $message);
        file_put_contents($path, $line, FILE_APPEND);
    }
}
