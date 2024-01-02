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
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;

class LoiApplicantListExport implements ExportContract, FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithColumnFormatting, WithEvents
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
            'الاسم_الكامل',
            'الجنسية',
            'رقم_الجواز',
            "عنوان الاقامة داخل العراق",
            "اسم المنفذ الحدودي للدخول",
            "المهنة والوظيفة",
            "بلد الاقامة",
            'التأمينات',
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $event->sheet->getDelegate()->setRightToLeft(true);
            },
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
                $applicant->pax->nationality->country_name_short_ar ?? '',
                $applicant->pax->latestPassport->passport_no ?? '',
                "حقل مجنون - شركة انطونويل	",
                "مطار بغداد الدولي / مطار البصرة الدولي",
                $applicant->pax->arab_position || $applicant->pax->position ?? '',
                $applicant->pax->country_residence->country_name_short_ar ?? '',
                $applicant->generateRemarks() ?? '',
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
