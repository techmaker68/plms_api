<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\PLMSBloodTest;

class BloodTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $this->importOldBloodTestData();
    }
    public function importOldBloodTestData(){
        DB::table('plms_blood_tests')->delete();

        $table2Records = DB::select('SELECT * FROM plms_old_blood_tests');

        foreach ($table2Records as $record) {
            PLMSBloodTest::create([
                'batch_no' => $record->Batch_Number,
                'submit_date' => $record->submit_date,
                'test_date' => $record->test_date,
                'return_date' => $record->returned_date,
                'venue' => $record->Venue,
                // Add other fields as needed
            ]);
        }
        return 'Imported Successfully!';
    }
}
