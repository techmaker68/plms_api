<?php

namespace App\Contracts;

interface ExcelImportServiceContract
{
    public function downloadExcel($data);
    public function importView($data);
}
