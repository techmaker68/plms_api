<?php
// ***********************************
// @author Syed, Umair
// @create_date 06-11-2023
// ***********************************
namespace App\Exports;

use App\Contracts\ExportContract;
use Illuminate\Support\Collection;
use DateTime;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class VisaExport implements ExportContract, FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithColumnFormatting
{
    protected $data = [];
    public function __construct($data)
    {
        $this->data = $data;
    }
    public function headings(): array
    {
        return [
            "Pax Id",
            "Full Name",
            "Type",
            "Passport No",
            "Employer",
            "Department",
            "Date Of Issue",
            "Date Of Expire",
            "Expire in Days",
            "Status",
        ];
    }
    public function collection(): Collection
    {

        $formattedData = $this->data->map(function ($item) {
            $expirationDate = $item->date_of_expiry;
            if ($expirationDate === NULL) {
                $expirationDate = date('Y-m-d');
            }
            $days = $this->daysPassedAndRemaining($expirationDate);
            $pax = $item->pax ?? null;
            $passportNo =  optional($pax)->latestPassport->passport_no ?? null;
            $company =  optional($pax)->company->name ?? null;
            $pax_dep = optional($pax)->department->name ?? null;
            return [
                $pax->pax_id ?? $item->pax_id,
                $pax->eng_full_name ?? $item->full_name,
                $item->type,
                $passportNo,
                $company ?? $item->employer,
                $pax_dep ?? $item->department,
                $item->date_of_issue,
                $item->date_of_expiry,
                $days['remaining'],
                ($item->status == 1) ? 'Approved' : (($item->status == 2) ? 'Expired' : (($item->status == 3) ? 'TO be Renewed' : 'Cancelled')),
            ];
        });
        return $formattedData;
    }
    public function styles(Worksheet $sheet)
    {


        return [
            'A' => ['alignment' => ['vertical' => 'center', 'horizontal' => 'center']],
            'B' => ['alignment' => ['vertical' => 'center', 'horizontal' => 'center']],
            'C' => ['alignment' => ['vertical' => 'center', 'horizontal' => 'center']],
            'D' => ['alignment' => ['vertical' => 'center', 'horizontal' => 'center']],
            'E' => ['alignment' => ['vertical' => 'center', 'horizontal' => 'center']],
            'F' => ['alignment' => ['vertical' => 'center', 'horizontal' => 'center']],
            'G' => ['alignment' => ['vertical' => 'center', 'horizontal' => 'center']],
            'H' => ['alignment' => ['vertical' => 'center', 'horizontal' => 'center']],
            'I' => ['alignment' => ['vertical' => 'center', 'horizontal' => 'center']],
            'J' => ['alignment' => ['vertical' => 'center', 'horizontal' => 'center']],
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
    function daysPassedAndRemaining($expirationDate)
    {

        $now = new DateTime();
        $expiration = DateTime::createFromFormat('Y-m-d', $expirationDate);
        $interval = $now->diff($expiration);

        $daysPassed = $interval->invert ? $interval->days : 0;
        $daysRemaining = $interval->invert ? 0 : $interval->days;

        return [
            'passed' => $daysPassed,
            'remaining' => $daysRemaining
        ];
    }
}
