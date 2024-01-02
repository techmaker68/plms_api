<?php
// ***********************************
// @author Syed, Umair, Majid
// @create_date 21-07-2023
// ***********************************
namespace App\Imports;

use App\Models\Country;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Modules\BloodTest\Entities\BloodTest;
use Modules\BloodTest\Entities\PLMSBloodApplicant;
use Modules\Passport\Entities\PLMSPassport;

class ImportOneTimePassports implements ToCollection, WithMultipleSheets
{
    /**
     * @param Collection $collection
     */
    private $batchNo;

    public function __construct($batchNo)
    {
        if (!$batchNo) {
            $this->batchNo = BloodTest::latest()->first()->batch_no;
        } else {
            $this->batchNo = $batchNo;
        }
    }
    public function collection(Collection $rows)
    {
        $rows->shift();
        $rows->shift();
        foreach ($rows as $row) {
            $nationalityCode = Country::extractCountryCode($row[2]);
            $nationality = Country::getCountryId($nationalityCode);

            $passport = PLMSPassport::where('passport_no',  str_replace(' ', '', $row[1]))->first();
            $batch = BloodTest::where('batch_no', $this->batchNo)->first();
            $sequence_no = PLMSBloodApplicant::where('batch_no', $this->batchNo)->max('sequence_no')+1;
            if ($passport) {
                $pax = $passport->pax;
                if ($pax) {
                    PLMSBloodApplicant::create([
                        'passport_no' =>  str_replace(' ', '', $row[1]),
                        'department' =>   $pax->department->name ?? null,
                        'employer' =>  $pax->company && $pax->company->name ? $pax->company->name  : '',
                        'pax_id' => $pax ? $pax->pax_id : null,
                        'full_name' => $pax->eng_full_name ?? '',
                        'nationality' => $pax->country->id ?? null,
                        'email' =>   $pax->email ?? '',
                        'phone' =>   $pax->mobile ?? null,
                        'batch_no' =>  $this->batchNo,
                        'appoint_date' =>  $batch ? $batch->test_date : null,
                        'sequence_no' =>$sequence_no,
                    ]);
                }
            } else {
                PLMSBloodApplicant::create([
                    'full_name' => $row[0],
                    'passport_no' =>  str_replace(' ', '', $row[1]),
                    'nationality' => $nationality ? $nationality : null,
                    'email' =>   $row[3],
                    'batch_no' =>  $this->batchNo,
                    'sequence_no' =>$sequence_no,
                ]);
            }
        }
    }
    public function sheets(): array
    {
        return [
            0 => $this, // Use the current class to process the first sheet.
        ];
    }
}
