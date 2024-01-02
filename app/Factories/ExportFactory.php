<?php

namespace App\Factories;

use App\Contracts\ExportContract;
use Illuminate\Support\Str;

class ExportFactory
{
    const CLASSPATH = 'App\\Exports\\';

    public static function make(string $type, $data): ExportContract
    {
        $className = self::getClassName($type);
        if (class_exists($className) && is_subclass_of($className, ExportContract::class)) {
            return new $className($data);
        }

        throw new \InvalidArgumentException("Invalid Export type: $type");
    }

    private static function getClassName($template)
    {
        $path = self::CLASSPATH;

        $className = Str::studly($template);

        return $path . $className . 'Export';
    }
}
