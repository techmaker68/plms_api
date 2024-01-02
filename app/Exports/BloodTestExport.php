<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use App\Contracts\ExportContract;
use Illuminate\Support\Collection;

class BloodTestExport implements ExportContract, FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithColumnFormatting
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $applicants;
    public function __construct($applicants)
    {
        $this->applicants = $applicants;
    }

    public function headings(): array
    {
        return [
            "Name as per Passport",
            "Passport No",
            "Nationality",
            "Department",
            "Email Address",
            "Phone No",
        ];
    }
    public function collection(): Collection
    {
        $filteredApplicants = $this->applicants->filter(function ($applicant) {
            return $applicant->pax !== null;
        });

        $formattedData = $filteredApplicants->map(function ($applicant) {
            return [
                $applicant->pax->latestPassport->full_name ?? '',
                $applicant->pax->latestPassport->passport_no ?? '',
                $applicant->pax->countryResidence->country_name_short_ar ?? '',
                $applicant->pax->department->name ?? '',
                $applicant->pax->email ?? $applicant->email,
                $applicant->pax->phone ?? $applicant->phone,
            ];
        });

        return $formattedData;
    }
    public function styles(Worksheet $sheet)
    {
        return [
            'A' => ['width' => 10],
            'B' => ['width' => 5],
        ];
    }
    public function columnFormats(): array
    {
        return [
            'A' => 'General',
            'B' => 'General',
        ];
    }
}
