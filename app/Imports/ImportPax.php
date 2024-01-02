<?php
// ***********************************
// @author Syed, Umair, Majid
// @create_date 21-07-2023
// ***********************************
namespace App\Imports;

use App\Models\Country;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Modules\Company\Entities\PLMSCompany;
use Modules\Department\Entities\PLMSDepartment;
use Modules\Passport\Entities\PLMSPassport;
use Modules\Pax\Entities\PLMSPax;

class ImportPax implements ToCollection, WithMultipleSheets
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
        $rows = $rows->slice(3);

        foreach ($rows as $row) {
            $maxId = PLMSPax::select('pax_id')->max('pax_id');
            $maxId = $maxId + 1;
            $maxId = str_pad($maxId, 6, '0', STR_PAD_LEFT);
            // Using indices for accessing the cell values
            $countryCode = Country::extractCountryCode($row[9]);
            $country = Country::getCountryId($countryCode);
            $residenceCountryCode = Country::extractCountryCode($row[10]);
            $residence = Country::getCountryId($residenceCountryCode);
            $issueCountryCode = Country::extractCountryCode($row[21]);
            $passport_place_of_issue = Country::getCountryId($issueCountryCode);
            if (!empty($row[13])) {
                $company = PLMSCompany::where('name', 'like', '%' . $row[13] . '%')
                    ->orWhere('short_name', 'like', '%' . $row[13] . '%')
                    ->orWhere('poc_name', 'like', '%' . $row[13] . '%')
                    ->first();
            } else {
                $company = null;
            }
            if (!empty($row[15])) {
                $department = PLMSDepartment::where('name', 'like', '%' . $row[15] . '%')
                    ->orWhere('abbreviation', 'like', '%' . $row[15] . '%')
                    ->first();
            } else {
                $department = null;
            }
            $pax = PLMSPax::where('badge_no', $row[0])->first();
            if (!$pax || empty($row[0])) {
                $newpax =  PLMSPax::create([
                    'badge_no' => $row[0] ? str_replace(' ', '', $row[0]) : null,
                    'pax_id' => $maxId,
                    'type' =>  ucfirst(str_replace(' ', '', $row[1])),
                    'email' =>  str_replace(' ', '', $row[2]),
                    'dob' =>  $this->formatDate($row[3]),
                    'gender' =>  str_replace(' ', '', $row[4]) ?? null,
                    'first_name' => $row[5],
                    'last_name' => $row[6],
                    'eng_full_name' => $row[7],
                    'arab_full_name' => $row[8],
                    'nationality' => $country ? $country : null,
                    'country_residence' => $residence ? $residence : null,
                    'position' => $row[11],
                    'arab_position' => $row[12],
                    'company_id' => $company ? $company->id : null,
                    'phone' =>  str_replace(' ', '', $row[14]),
                    'department_id' => $department ? $department->id : null,
                ]);
                if ($newpax) {
                    PLMSPassport::create([
                        'pax_id' =>  $newpax->pax_id,
                        'full_name' => $row[16],
                        'passport_no' =>  str_replace(' ', '', $row[17]),
                        'type' => str_replace(' ', '', $row[18]),
                        'date_of_issue' =>  $this->formatDate($row[19]),
                        'date_of_expiry' =>  $this->formatDate($row[20]),
                        'birthday' =>  $this->formatDate($row[3]),
                        'gender' => ucfirst(str_replace(' ', '',  $row[4])) == 'M' ? 'Male' : 'Female',
                        'passport_country' => $country ? $country : null,
                        'place_of_issue' => $passport_place_of_issue ? $passport_place_of_issue : null,
                    ]);
                }
            }
        }
    }

    public function formatDate($date)
    {
        if (is_numeric($date)) {
            return Carbon::createFromFormat('Y-m-d', '1900-01-01')->addDays((int)$date - 2)->format('Y-m-d');
        } else {
            return null;
        }
    }

    public function sheets(): array
    {
        return [
            0 => $this, // Use the current class to process the first sheet.
        ];
    }
}
