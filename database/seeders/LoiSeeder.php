<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\PLMSPax;
use App\Models\PLMSLOI;

class LoiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $this->importOldLoiData();
    }
    public function importOldLoiData(){

        // loi
    
        DB::table('plms_lois')->delete();
        $table2Records = DB::select('SELECT * FROM plms_old_lois');
    // // dd($table1Records);
        foreach ($table2Records as $record) {
            if($record->boc_hq_date == '0000-00-00'){
                $boc_hq_date = null;
                // dd($record);
              }else{
                $boc_hq_date = $record->boc_hq_date;
              }
            if($record->moo_date == '0000-00-00'){
                $moo_date = null;
                // dd($record);
              }else{
                $moo_date = $record->moo_date;
              }
            if($record->moi_payment_date == '0000-00-00'){
                $moi_payment_date = null;
                // dd($record);
              }else{
                $moi_payment_date = $record->moi_payment_date;
              }
            if($record->loi_sent_date == '0000-00-00'){
                $loi_sent_date = null;
                // dd($record);
              }else{
                $loi_sent_date = $record->loi_sent_date;
              }
            if($record->loi_closed_date == '0000-00-00'){
                $loi_closed_date = null;
                // dd($record);
              }else{
                $loi_closed_date = $record->loi_closed_date;
              }
            if($record->submission_date == '0000-00-00'){
                $submission_date = null;
                // dd($record);
              }else{
                $submission_date = $record->submission_date;
              }
            if($record->loi_issue_date == '0000-00-00'){
                $loi_issue_date = null;
                // dd($record);
              }else{
                $loi_issue_date = $record->loi_issue_date;
              }
            if($record->loi_number == ''){
                $loi_number = null;
                // dd($record);
              }else{
                $loi_number = $record->loi_number;
              }
              if($record->boc_mfd_date == '0000-00-00'){
                $boc_mfd_date = null;
                // dd($record);
              }else{
                $boc_mfd_date = $record->boc_mfd_date;
              }
            PLMSLOI::create([
                'nation_category' => $record->nation_category,
                'batch_no' => $record->batch_number,
                'loi_type' => $record->loi_type,
                'submission_date' => $submission_date,
                'mfd_date' => $boc_mfd_date,
                'mfd_ref' => $record->boc_mfd_ref,
                'hq_date' => $boc_hq_date,
                'hq_ref' => $record->boc_hq_ref,
                'moo_date' => $moo_date,
                'moo_ref' => $record->moo_ref,
                'moi_payment_date' => $moi_payment_date,
                'moi_invoice' => $record->moi_invoice_number,
                'moi_deposit' => $record->moi_insurancedeposite_amount,
                'loi_issue_date' => $loi_issue_date,
                'loi_no' => $loi_number,
                'sent_loi_date' => $loi_sent_date,
                'close_date' => $loi_closed_date,
                // Add other fields as needed
            ]);
        }
        return 'Imported Successfully!';
    }
        
}
