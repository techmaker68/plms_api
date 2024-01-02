<?php
// ***********************************
// @author Syed, Umair, Majid
// @create_date 21-07-2023
// ***********************************
namespace App\Imports;


use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Modules\BloodTest\Entities\PLMSBloodApplicant;
use Modules\Pax\Entities\PLMSPax;

class ImportBloodApplicants implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        $rows->shift();


        foreach ($rows as $row) {
            $pax = PLMSPax::where('badge_no',  str_replace(' ', '', $row[0]))->first();
            $status = '';
            if ($row[10] == 'tested') {
                $status = 0;
            } else if ($row[10] == 'no show') {
                $status = 1;
            } else {
                $status = 2;
            }
            if ($pax) {
                PLMSBloodApplicant::create([
                    'batch_no' =>  str_replace(' ', '', $row[1]),
                    'pax_id' => $pax ? $pax->pax_id : null,
                    'appoint_time' => $row[2],
                    'appoint_date' =>  $this->formatDate($row[3]),
                    'blood_test_types' => strtoupper($row[4]),
                    'arrival_date' =>  $this->formatDate($row[5]),
                    'departure_date' =>   $this->formatDate($row[6]),
                    'test_purposes' =>   $row[7],
                    'passport_submit_date' =>   $this->formatDate($row[8]),
                    'passport_return_date' =>   $this->formatDate($row[9]),
                    'attendance' =>   $row[10],
                    'hbs_expire_date' =>   $this->formatDate($row[11]),
                    'remarks' =>   $row[12],
                ]);
            }
        }
    }

    function formatDate($date)
    {
        if (is_numeric($date)) {
            return Carbon::createFromFormat('Y-m-d', '1900-01-01')->addDays((int)$date - 2)->format('Y-m-d');
        } else {
            return Carbon::parse(str_replace(' ', '', $date))->format('Y-m-d');
        }
    }
}
