<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\PLMSPax;

use App\Models\PLMSPassport;

class PassportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $this->importOldPassportData();
    }
     public function importOldPassportData(){
        DB::table('plms_passports')->delete();
    $table2Records = DB::select('SELECT * FROM plms_old_passports');
    // dd($table1Records);
    foreach ($table2Records as $record) {
        if($record->date_of_birth ==  '0000-00-00'){
            $date_of_birth = null;
            // dd($record);
          }else{
            $date_of_birth = $record->date_of_birth;
          }
          $pax_id = PLMSPAX::where('pax_id',$record->plms_id)->first();
          if($pax_id){
            $old_plms_id = $pax_id->pax_id;
          }else{
            $old_plms_id = null;
          }

         PLMSPassport::create([
            'pax_id' => $old_plms_id,
            'type' => $record->passport_type,
            'full_name' => $record->full_name_passport,
            'arab_full_name' => $record->full_name_arabic,
            'passport_no' => $record->passport_number,
            'date_of_expiry' => $record->date_of_expiry,
            'date_of_issue' => $record->date_of_issue,
            'birthday' => $date_of_birth,
            'status' => $record->status,
        ]);
    }
    return 'Imported Successfully!';
    }
}
