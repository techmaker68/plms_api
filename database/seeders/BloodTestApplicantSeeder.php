<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\BloodTest\Entities\PLMSBloodApplicant;
use App\Models\PLMSPax;
use App\Models\PLMSBloodTest;

class BloodTestApplicantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $this->importOldApplicantData();
    }
    public function importOldApplicantData(){
        DB::table('plms_blood_applicants')->delete();
    
      $table2Records = DB::select('SELECT * FROM plms_old_blood_applicants');
    // // dd($table1Records);
        foreach ($table2Records as $record) {
          if($record->blood_test_date == '0000-00-00'){
            $blood_test_date = null;
            // dd($record);
          }else{
            $blood_test_date = $record->blood_test_date;
          }
          if($record->passport_number == '0'){
            $passport_number = null;
            // dd($record);
          }else{
            $passport_number = $record->passport_number;
          }
          $pax_id = PLMSPAX::where('pax_id',$record->pax_id)->first();
          if($pax_id){
            $old_plms_id = $pax_id->pax_id;
          }else{
            $old_plms_id = null;
          }
          $batch_number = PLMSBloodTest::where('batch_no',$record->batch_number)->first();
          if($batch_number){
            $old_batch_number = $batch_number->batch_no;
          }else{
            $old_batch_number = null;
          }
    
          PLMSBloodApplicant::create([
                'pax_id' => $old_plms_id,
                'batch_no' => $old_batch_number,
                'arrival_date' => $record->arrival_date,
                'departure_date' => $record->departure_date,
                'passport_submit_date' => $record->passport_received_date,
                'passport_return_date' => $record->passport_return_date,
                'appoint_date' =>  $blood_test_date,
                'appoint_time' => $record->Test_Appoint_Time,
                'attendance' => $record->Test_Status,
                'test_purposes' => $record->test_purposes,
                'blood_test_types' => $record->blood_test_type,
                'remarks' => $record->remarks,
                'passport_no' => $passport_number,
                'full_name' => $record->fullname,
                'employer' => $record->employer,
                'badge_no' => $record->badge_number,
                'email' => $record->email,
                'department' => $record->department,
                'phone' => $record->mobile,
                'nationality' => $record->nationality_id,
                // Add other fields as needed
            ]);
            
          }
          return 'Imported Successfully!';
      }
}
