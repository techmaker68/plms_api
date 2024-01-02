<?php

namespace Database\Seeders;

use App\Models\PLMSDepartment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\PLMSPax;

class PaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        /// seed for paxes
     $this->importOldPlmsData();
    }
    public function importOldPlmsData()
    {

        DB::table('plms_paxes')->delete();
        $table1Records = DB::select('SELECT * FROM plms_profiles');
        foreach ($table1Records as $record) {
            $department = PLMSDepartment::where('abbreviation', $record->department)->first();
            if($department){
                $department_id = $department->id;
            }else{

                $department_id = null;
            }
           $pax= PLMSPax::create([
                'pax_id' => $record->pmls_id,
                'company_id' => 1,
                'type' => $record->personnel_type,
                'first_name' => $record->first_name,
                'last_name' => $record->last_name,
                'eng_full_name' => $record->full_name,
                'arab_full_name' => $record->arabic_name,
                'nationality' => $record->nationality_id,
                'position' => $record->position,
                'department_id' => $department_id,
                'phone' => $record->mobile,
                'email' => $record->email,
                'gender' => $record->sex,
                'badge_no' => $record->badge_number,
                'status' => $record->active,
            ]);
        }
        return 'Imported Successfully!';
    }
}
