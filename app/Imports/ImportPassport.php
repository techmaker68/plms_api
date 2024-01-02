<?php
// ***********************************
// @author Syed, Umair, Majid
// @create_date 21-07-2023
// ***********************************
namespace App\Imports;

use App\Models\Country;
use Modules\Pax\Entities\PLMSPax;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Modules\Passport\Entities\PLMSPassport;

class ImportPassport implements ToCollection
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

            $countryCode = Country::extractCountryCode($row[9]);
            $country = Country::getCountryId($countryCode);
            $residenceCountryCode = Country::extractCountryCode($row[8]);
            $residence = Country::getCountryId($residenceCountryCode);
            $pax = PLMSPax::where('badge_no',  str_replace(' ', '', $row[0]))->first();
            $passport = PLMSPassport::where('passport_no',  str_replace(' ', '', $row[2]))->first();
            if (is_numeric($row[4])) {
                $date_of_issue = Carbon::createFromFormat('Y-m-d', '1900-01-01')->addDays((int)$row[4] - 2)->format('Y-m-d');
            } else {
                $date_of_issue = Carbon::parse(str_replace(' ', '', $row[4]))->format('Y-m-d');
            }
            if (is_numeric($row[4])) {
                $date_of_expiry = Carbon::createFromFormat('Y-m-d', '1900-01-01')->addDays((int)$row[5] - 2)->format('Y-m-d');
            } else {
                $date_of_expiry = Carbon::parse(str_replace(' ', '', $row[5]))->format('Y-m-d');
            }
            if (is_numeric($row[4])) {
                $birthday = Carbon::createFromFormat('Y-m-d', '1900-01-01')->addDays((int)$row[6] - 2)->format('Y-m-d');
            } else {
                $birthday = Carbon::parse(str_replace(' ', '', $row[6]))->format('Y-m-d');
            }
            if ($pax && !$passport) {
                PLMSPassport::create([
                    'pax_id' => $pax ? $pax->pax_id : null,
                    'full_name' => $row[1],
                    'passport_no' =>  str_replace(' ', '', $row[2]),
                    'type' => str_replace(' ', '', $row[3]),
                    'date_of_issue' =>  $date_of_issue,
                    'date_of_expiry' =>  $date_of_expiry,
                    'birthday' =>  $birthday,
                    'passport_country' => $country ? $country : null,
                    'place_of_issue' => $residence ? $residence : null,
                ]);
            }
        }
    }
}
