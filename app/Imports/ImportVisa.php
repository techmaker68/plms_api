<?php
// ***********************************
// @author Syed, Umair, Majid
// @create_date 21-07-2023
// ***********************************
namespace App\Imports;


use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Modules\Pax\Entities\PLMSPax;
use Modules\Visa\Entities\PLMSVisa;

class ImportVisa implements ToCollection
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function getCsvSettings(): array
    {
        return [
            'input_encoding' => 'UTF-8',
            'delimiter' => ',',
            'enclosure' => '"',
            'escape' => '\\',
        ];
    }
    public function collection(Collection $rows)
    {
        // Remove the first row (header)
        $rows->shift();

        foreach ($rows as $row) {
            $pax = PLMSPax::where('badge_no',  str_replace(' ', '', $row[0]))->first();
            if (is_numeric($row[4])) {
                $date_of_issue = Carbon::createFromFormat('Y-m-d', '1900-01-01')->addDays((int)$row[2] - 2)->format('Y-m-d');
            } else {
                $date_of_issue = Carbon::parse(str_replace(' ', '', $row[2]))->format('Y-m-d');
            }
            if (is_numeric($row[4])) {
                $date_of_expiry = Carbon::createFromFormat('Y-m-d', '1900-01-01')->addDays((int)$row[3] - 2)->format('Y-m-d');
            } else {
                $date_of_expiry = Carbon::parse(str_replace(' ', '', $row[3]))->format('Y-m-d');
            }
            if ($pax) {
                PLMSVisa::create([
                    'pax_id' => $pax ? $pax->pax_id : null,
                    'full_name' => '',
                    'type' =>  strtolower(str_replace(' ', '', $row[1])) == 'visitor'  ? ucfirst(str_replace(' ', '', $row[1])) :   $row[1], // Capitalize the first letter and assign it to 'type
                    'date_of_issue' => $date_of_issue,
                    'date_of_expiry' =>  $date_of_expiry,
                    'visa_no' => str_replace(' ', '', $row[4]),
                ]);
            }
        }
    }
}
