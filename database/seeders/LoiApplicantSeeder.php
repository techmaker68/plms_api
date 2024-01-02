<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\PLMSPax;
use App\Models\PLMSLOIApplicant;
use App\Models\PLMSLOI;
class LoiApplicantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $this->importOldLoiApplicantData();
    }
    public function importOldLoiApplicantData(){

        // loi applicants
        DB::table('plms_loi_applicants')->delete();

    $table2Records = DB::select('SELECT * FROM plms_old_loi_applicants');
        foreach ($table2Records as $record) {
            $pax_id = PLMSPAX::where('pax_id',$record->plms_id)->first();
            if($pax_id){
            $old_plms_id = $pax_id->pax_id;
            }else{
            $old_plms_id = null;
            }
            $batch_number = PLMSLOI::where('batch_no',$record->loi_batch_number)->first();
            if($batch_number){
            $old_batch_number = $batch_number->batch_no;
            }else{
            $old_batch_number = null;
            }
            PLMSLOIApplicant::create([
                'pax_id' => $old_plms_id,
                'batch_no' => $old_batch_number,
                'status' => $record->application_status,
                'remarks' => $record->remarks,
                'full_name' => $record->full_name,
                'passport_no' => $record->passport_number,
                'arab_full_name' => $record->name_arabic,
                'department' => $record->department,
                'employer' => $record->employer,
                'email' => $record->email,
                'phone' => $record->mobile,
            ]);
        }
        return 'Imported Successfully!';
    }
}
