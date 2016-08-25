<?php
declare (strict_types = 1);

namespace Bacon\Config;

final class Config
{
    /**
     * @return array
     */
    public static function get(): array
    {
        return include __DIR__ . '/../../config/local.php';
    }
}
