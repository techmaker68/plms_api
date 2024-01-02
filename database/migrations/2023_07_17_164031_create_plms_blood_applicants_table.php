<?php
// ***********************************
// @author Syed, Aqsa, Saqib
// @create_date 21-07-2023
// ***********************************
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlmsBloodApplicantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plms_blood_applicants', function (Blueprint $table) {
            $table->id();
            $table->integer('pax_id')->nullable();
            $table->foreign('pax_id')->references('pax_id')->on('plms_paxes')->onDelete('cascade');
            $table->integer('batch_no')->nullable(); 
            $table->foreign('batch_no')->references('batch_no')->on('plms_blood_tests')->onDelete('cascade');
            $table->date('arrival_date')->nullable();
            $table->date('departure_date')->nullable();
            $table->date('passport_submit_date')->nullable();
            $table->date('hiv_expire_date')->nullable();
            $table->date('hbs_expire_date')->nullable();
            $table->date('passport_return_date')->nullable();
            $table->date('appoint_date')->nullable();
            $table->time('appoint_time')->nullable();
            $table->integer('attendance')->comment('1= tested , 2=no show , 3=no need to test')->nullable();
            $table->text('test_purposes')->nullable();
            $table->text('task_purposes')->nullable();
            $table->string('blood_test_types')->nullable(); // use comma
            $table->text('remarks')->nullable();
            $table->string('passport_no')->nullable();
            $table->string('full_name')->nullable(); 
            $table->integer('passport_country')->nullable();
            $table->date('birthday')->nullable();
            $table->date('passport_issue_date')->nullable();
            $table->date('passport_expiry_date')->nullable();
            $table->string('employer')->nullable();
            $table->integer('badge_no')->nullable();
            $table->string('position')->nullable();
            $table->string('department')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->integer('nationality')->nullable();
            $table->tinyInteger('scheduled_status')->default(0);
            $table->integer('sequence_no')->nullable()->default(0);
            $table->date('new_appoint_date')->nullable();
            $table->text('new_remarks')->nullable();
            $table->text('penalty_remarks')->nullable();
            $table->string('penalty_fee')->nullable();
            $table->integer('country_code_id')->nullable();
            $table->text('visa_penalty_remarks')->nullable();
            $table->string('visa_penalty_fee')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plms_blood_applicants');
    }
}
