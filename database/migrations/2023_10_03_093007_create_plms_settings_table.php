<?php
// ***********************************
// @author Syed, Aqsa
// @create_date 21-07-2023
// ***********************************
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePlmsSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plms_settings', function (Blueprint $table) {
            $table->id();
            $table->string('parameter')->unique(); // make it unique to avoid duplicate entries
            $table->text('value')->nullable();
            $table->text('details')->nullable();
            $table->timestamps();
        });
    
        // Inserting default parameters
        DB::table('plms_settings')->insert([
            ['parameter' => 'blood_test_emails', 'value' => '00:00' , 'details' => ''],
            ['parameter' => 'blood_test_email_admins', 'value' => '' , 'details' => ''],
            ['parameter' => 'blood_test_email_status', 'value' => '1' , 'details' => ''],
            ['parameter' => 'loi_admin_emails', 'value' => '' , 'details' => ''],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plms_settings');
    }
}
