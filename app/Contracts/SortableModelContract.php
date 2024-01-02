<?php

namespace App\Contracts;

interface SortableModelContract
{
    public function sort(array $ids): void;
}
