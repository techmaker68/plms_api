<?php

namespace App\Exports;

use DateTime;
use App\Contracts\ExportContract;
use Illuminate\Support\Collection;
use Modules\BloodTest\Entities\BloodTest;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Modules\BloodTest\Entities\PLMSBloodApplicant;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;


class KpiExport implements ExportContract, FromCollection, WithHeadings, WithStyles, WithColumnFormatting
{

    protected $data = [];
    protected $batches = [];
    protected $batchNo = [];
    protected $dates = [];

    public function __construct($data)
    {
        $this->data = $data;
        $this->batches = PLMSBloodApplicant::whereIn('batch_no', $this->data->pluck('batch_no')->toArray())->get();
        $this->batchNo = array_unique($this->batches->pluck('batch_no')->toArray());
        $this->dates = array_unique($this->data->pluck('test_date')->toArray());
    }
    /**
     * @return \Illuminate\Support\Collection
     */

    public function headings(): array
    {
        $headings = ['Type'];
        foreach ($this->dates as $date) {
            $date = $this->getFormattedDate($date);
            $headings[] = $date;
        }
        return $headings;
    }

    public function collection(): Collection
    {
        $applicants = $this->batches;
        $data = [];

        $data['No. staff Blood test'] = array_fill_keys($this->batchNo, 0);
        $data['No. HIV'] = array_fill_keys($this->batchNo, 0);
        $data['No. HBS'] = array_fill_keys($this->batchNo, 0);
        $data['No. Malaria'] = array_fill_keys($this->batchNo, 0);
        $data['No. Iraq MEEV'] = array_fill_keys($this->batchNo, 0);
        $data['No. MEEV in country'] = array_fill_keys($this->batchNo, 0);
        $data['No. Visa Cancellations'] = array_fill_keys($this->batchNo, 0);
        $data['No. Contractors Blood test'] = array_fill_keys($this->batchNo, 0);
        // Initialize data array with zeros
        foreach ($applicants as $applicant) {
            $taskPurposes = is_array($applicant->task_purposes) ? $applicant->task_purposes : json_decode($applicant->task_purposes, true);
            if ($taskPurposes) {
                foreach ($taskPurposes as  $detail) {
                    if ($detail['status'] && isset($detail['values'])) {
                        if (is_array($detail['values'])) {
                            $values = $detail['values'];
                        } else {
                            $values = explode('+', $detail['values']);
                        }
                        foreach ($values as $value) {
                            $renamedValue = '';
                            switch ($value) {
                                case 'HIV':
                                    $renamedValue = 'No. HIV';
                                    break;
                                case 'HBS':
                                    $renamedValue = 'No. HBS';
                                    break;
                                case 'M':
                                    $renamedValue = 'No. Malaria';
                                    break;
                                case 'Renewal':
                                    $renamedValue = 'No. Renewal';
                                    break;
                                case 'Extension':
                                    $renamedValue = 'No. Extension';
                                    break;
                                default:
                                    $renamedValue = $value;
                            }
                            $data[$renamedValue][$applicant->batch_no] = ($data[$renamedValue][$applicant->batch_no] ?? 0) + 1;
                        }
                    }
                }
                if (isset($taskPurposes['blood']) && $taskPurposes['blood']['status']) {
                    $data['No. staff Blood test'][$applicant->batch_no]++;
                }
                if (isset($taskPurposes['meev']) && $taskPurposes['meev']['status']) {
                    $data['No. MEEV in country'][$applicant->batch_no]++;
                }
                if (isset($taskPurposes['visa']) && $taskPurposes['visa']['status']) {
                    $data['No. Visa Cancellations'][$applicant->batch_no]++;
                }
            }
        }

        // Calculate totals for each type across batchNo
        $totals = [];
        foreach ($this->batchNo as $batch) {
            $totals[$batch] = array_sum(array_column($data, $batch));
        }

        // Prepare the data for export
        $exportData = [];
        $value = 'No. staff Blood test';
        $prefixedValue = "$value";
        $rowData = [$prefixedValue];
        foreach ($this->batchNo as $batch) {
            $rowData[] = $data[$value][$batch] ?? 0;
        }
        $exportData[] = $rowData;

        // Add other rows
        foreach (array_keys($data) as $value) {
            if ($value !== 'No. staff Blood test') {
                $prefixedValue = "$value";
                $rowData = [$prefixedValue];
                foreach ($this->batchNo as $batch) {
                    $rowData[] = $data[$value][$batch] ?? 0;
                }
                $exportData[] = $rowData;
            }
        }
        // Add "Total" row
        $totalRow = ['Total'];
        foreach ($this->batchNo as $batch) {
            $totalRow[] = $totals[$batch] ?? 0;
        }
        $exportData[] = $totalRow;
        // Include only specific rows in the export
        $includedRows = [
            'No. staff Blood test',
            'No. HIV',
            'No. HBS',
            'No. Malaria',
            'No. Iraq MEEV',
            'No. MEEV in country',
            'No. Visa Cancellations',
            'No. Contractors Blood test',
            'Total',
        ];

        $filteredExportData = array_filter($exportData, function ($row) use ($includedRows) {
            return in_array($row[0], $includedRows);
        });
        return collect(array_values($filteredExportData));
    }

    protected function getFormattedDate($date)
    {
        $dateObject = new DateTime($date);
        $formattedDate = $dateObject->format('d-M-Y');
        return $formattedDate;
    }
    public function styles(Worksheet $sheet)
    {
        return [
            'A1' => ['font' => ['bold' => true]],
            'A' => ['width' => 20],
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
