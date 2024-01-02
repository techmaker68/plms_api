<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\PLMSPax;
use App\Models\PLMSVisa;
class VisaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $this->importOldVisaData();
    }
    public function importOldVisaData(){
        DB::table('plms_visas')->delete();

        $table2Records = DB::select('SELECT * FROM plms_old_visas');
        foreach ($table2Records as $record) {
            $pax_id = PLMSPAX::where('pax_id',$record->plms_id)->first();
            if($pax_id){
              $old_plms_id = $pax_id->pax_id;
            }else{
              $old_plms_id = null;
            }
            PlmsVisa::create([
                'pax_id' => $old_plms_id,
                'type' => $record->type,
                'full_name' => $record->full_name_in_visa,
                'loi_no' => $record->loi_number,
                'date_of_issue' => $record->issue_date,
                'date_of_expiry' => $record->expiry_date,
                'visa_no' => $record->visa_number,
                'passport_no' => $record->passport_number,
                'status' => $record->status,
            ]);
        }
        return 'Imported Successfully!';
    }
}
