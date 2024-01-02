<?php
namespace App\Utils;

class AppEnv
{
    const LOCAL = 'local';
    const DEVELOPMENT = 'development';
    const STAGING = 'staging';
    const PRODUCTION = 'production';

    public static $types = [
        self::LOCAL=>self::LOCAL,
        self::DEVELOPMENT=>self::DEVELOPMENT,
        self::STAGING=>self::STAGING,
        self::PRODUCTION=>self::PRODUCTION,
    ];
}
