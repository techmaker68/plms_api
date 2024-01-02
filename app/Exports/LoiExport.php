<?php
// ***********************************
// @author Syed, Umair
// @create_date 07-11-2023
// ***********************************
namespace App\Exports;

use App\Models\PLMSLOI;
use App\Models\PLMSLOIApplicant;
use Illuminate\Support\Collection;
use App\Contracts\ExportContract;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class LoiExport implements ExportContract, FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithColumnFormatting
{

    protected $data = [];
    public function __construct($data)
    {
        $this->data = $data;
    }
    public function headings(): array
    {
        return [
            "Batch #",
            "#",
            "Name as per Pax",
            "Name as per Passport",
            "Status",
            "Department",
            "Position",
            "Company",
            "Nationality",
            "Passport No",
            "Importance",
            "Loi Type",
            "Submitted to BOC MFD",
            "Recieved to BOC MFD",
            "Submitted to BOC HQ",
            "Received to BOC HQ",
            'Submitted to MOO',
            'MOO referance letter and date',
            'MOI referance letter and date',
            'LOI Status',
            'LOI expected issuance month',
            'Remarks',
        ];
    }

    public function collection(): Collection
    {
        $usersData = $this->data;
        if (!empty($usersData)) {
            $formattedData = $usersData->map(function ($item) {
                return [
                    $item->batch_no ?? '',
                    $item->sequence_no ?? '',
                    $item->pax->eng_full_name ?? '',
                    $item->latestPassport->full_name ?? '',
                    $item->getLoiStaatus() ?? '',
                    $item->pax->department->name ?? '',
                    $item->pax->position ?? '',
                    $item->pax->company->name ?? '',
                    $item->pax->country->nationality_en ?? '',
                    $item->latestPassport->passport_no ?? '',
                    $this->getPriorityById($item->loi_application->priority) ?? '',
                    $item->loi_application->getLoiType()  ?? '',
                    $item->loi_application->mfd_submit_date ?? '',
                    $item->loi_application->mfd_received_date ?? '',
                    $item->loi_application->hq_submit_date ?? '',
                    $item->loi_application->hq_received_date ?? '',
                    $item->loi_application->boc_moo_submit_date ?? '',
                    ($item->loi_application->boc_moo_ref || $item->loi_application->moo_date) ?  'Ref: ' . $item->loi_application->boc_moo_ref . ' Date: ' . $item->loi_application->boc_moo_date : '',
                    ($item->loi_application->moi_payment_letter_ref || $item->loi_application->moi_payment_letter_date) ? 'Ref: ' . $item->loi_application->moi_payment_letter_ref . ' Date: ' . $item->loi_application->moi_payment_letter_date : '',
                    $item->loi_application->loi_issue_date ? 'Issued' : '',
                    $item->loi_application->expected_issue_date ?? '',
                    $item->remarks ?? '',
                ];
            });
            return $formattedData;
        }
    }
    public function styles(Worksheet $sheet)
    {
        $batchColumns = ['A', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V'];

        foreach ($batchColumns as $column) {
            $sheet->getStyle($column)->getFont()->setBold(true);
        }

        $batchCount = [];
        $rowStart = 2;
        $startKey = 0;
        $chunkSize = 500;
        $data = $this->collection()->toArray();

        while ($startKey < count($data)) {
            $chunk = array_slice($data, $startKey, $chunkSize, true);
            foreach ($chunk as $key => $row) {
                $batchNumber = $row[0]; // Assuming 'A' is still used as the basis for merging

                foreach ($batchColumns as $column) {
                    if (!isset($batchCount[$batchNumber])) {
                        $batchCount[$batchNumber] = 1;
                    } else {
                        $batchCount[$batchNumber]++;
                    }

                    if ($batchCount[$batchNumber] == 1) {
                        $nextKey = $key + 1;
                        while ($nextKey < count($chunk) && $chunk[$nextKey][0] == $batchNumber) {
                            $nextKey++;
                        }

                        foreach ($batchColumns as $col) {
                            $sheet->mergeCells($col . ($rowStart + $startKey + $key) . ':' . $col . ($rowStart + $startKey + $nextKey - 1));
                            $sheet->getStyle($col . ($rowStart + $startKey + $key))->getAlignment()
                                ->setVertical('center')
                                ->setHorizontal('center');
                        }
                    }
                }
            }

            $startKey += $chunkSize;
        }

        $styles = [
            'A', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V',
        ];

        $styleArray = [
            'alignment' => ['vertical' => 'center', 'horizontal' => 'center'],
        ];

        $styleArrayBold = [
            'alignment' => ['vertical' => 'center', 'horizontal' => 'center'],
            'font' => ['bold' => true],
        ];

        foreach ($styles as $col) {
            $returnStyles[$col] = $styleArray;
            $returnStyles[$col . '1'] = $styleArrayBold;
        }

        return $returnStyles;
    }


    public function columnFormats(): array
    {
        return [
            'A' => 'General',
            'B' => 'General',
        ];
    }
    public function getPriorityById($type)
    {
        if ($type == 1) {
            return 'low';
        } else if ($type == 2) {
            return 'medium';
        } else {
            return 'high';
        }
    }
}
