<?php

namespace App\Factories;

use Illuminate\Support\Str;
use App\Sorters\LoiApplicantSorter;
use App\Sorters\BloodApplicantSorter;
use App\Contracts\SortableModelContract;

class SortingFactory
{
    const CLASSPATH = 'App\\Sorters\\';

    public static function make(string $type): SortableModelContract
    {
        $className = self::getClassName($type);

        if (class_exists($className) && is_subclass_of($className, SortableModelContract::class)) {
            return new $className($type);
        }

        throw new \InvalidArgumentException("Invalid Sort type: $type");
    }

    private static function getClassName($template)
    {
        $path = self::CLASSPATH;

        $className = Str::studly($template);

        return $path . $className . 'Sorter';
    }
}