<?php
// ***********************************
// @author Syed, Umair
// @create_date 31-10-2023
// ***********************************
namespace App\Exports;

use Illuminate\Support\Collection;
use App\Contracts\ExportContract;
use Modules\BloodTest\Entities\PLMSBloodApplicant;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Illuminate\Support\Str;



class BloodPenaltyExport implements ExportContract, FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithColumnFormatting
{
    protected $data;
    public function __construct($data)
    {
        $this->data = $data;
    }
    public function headings(): array
    {
        return [
            "Date of Blood Test",
            "Badge No",
            "Department",
            "Name",
            "Passport No",
            "Company",
            "Total Amount",
            "Visa Penalty",
            "Blood Test Penalty",
            "Blood Test Penalty Notes",
            "Visa Penalty Notes",
        ];
    }
    public function collection(): Collection
    {
        $applicants = $this->data->filter(function ($applicant) {
            return !is_null($applicant->visa_penalty_fee) || !is_null($applicant->penalty_fee);
        });
        
        $formattedData = $applicants->map(function ($item) {
            $penalty_fee = isset($item->penalty_fee) ? intval($item->penalty_fee) : 0;
            $visa_penalty_fee = isset($item->visa_penalty_fee) ? intval($item->visa_penalty_fee) : 0;
            return [
                $item->blood_test->test_date ?? '',
                $item->pax->badge_no ?? '',
                $item->pax->department->name ?? '',
                $item->pax->eng_full_name ?? $item->full_name,
                $item->pax && $item->pax->latestPassport ? $item->pax->latestPassport->passport_no : $item->passport_no,
                $item->pax->company->name ?? '',
                $penalty_fee + $visa_penalty_fee,
                $item->visa_penalty_fee ?? '',
                $item->penalty_fee ?? '',
                Str::of(strip_tags($item->penalty_remarks))->trim() ?? '',
                Str::of(strip_tags($item->visa_penalty_remarks))->trim() ?? '',
            ];
        });
        return $formattedData;
    }
    public function columnFormats(): array
    {
        return [
            'A' => 'General',
            'B' => 'General',
        ];
    }
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray([
            'font' => ['bold' => true],
        ]);
        return [
            'A1' => ['font' => ['bold' => true]],
            'A' => ['width' => 10],
            'B' => ['width' => 5],
        ];
    }
}
