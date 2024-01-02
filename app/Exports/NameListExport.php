<?php
// ***********************************
// @author Syed, Umair
// @create_date 07-11-2023
// ***********************************
namespace App\Exports;

use App\Contracts\ExportContract;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Loi\Entities\PLMSLoiApplicant;

class NameListExport implements ExportContract, FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithColumnFormatting, WithEvents
{
    protected $batch_no = '';
    public  function __construct($batch_no = 0)
    {
        $this->batch_no = $batch_no;
    }

    public function headings(): array
    {
        return [
            "الاسم",
            "الجنسية",
            "رقم الجواز",
            "عنوان الاقامة داخل العراق",
            "اسم المنفذ الحدودي للدخول",
            "المهنة والوظيفة",
            "بلد الاقامة",
            "التأمينات",

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
        $usersData =  PLMSLoiApplicant::where('batch_no', $this->batch_no)->orderby('sequence_no')->get();
        $formattedData = $usersData->map(function ($item) {
            return [
                $item->latestPassport->full_name ?? '',
                $item->pax->country->country_name_full_ar ??  '',
                $item->latestPassport->passport_no ?? '',
                'حقل مجنون - شركة انطونويل',
                'مطار بغداد الدولي / مطار البصرة الدولي',
                $item->pax->arab_position ?? '',
                $item->getCountryResidenceLoiArabicName() ?? '',
                $item->generateRemarks() ?? '',
            ];
        });

        return $formattedData;
    }
    public function styles(Worksheet $sheet)
    {


        return [
            'A1' => ['font' => ['bold' => true]],
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
