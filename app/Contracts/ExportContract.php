<?php

namespace App\Contracts;

use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

interface ExportContract
{
    public function headings(): array;
    public function collection(): Collection;
    public function columnFormats(): array;
    public function styles(Worksheet $sheet);
}
