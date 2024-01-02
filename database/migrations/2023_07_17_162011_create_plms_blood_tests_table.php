<?php
// ***********************************
// @author Syed, Aqsa, Saqib
// @create_date 21-07-2023
// ***********************************
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlmsBloodTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plms_blood_tests', function (Blueprint $table) {
            $table->id();
            $table->integer('batch_no')->unique()->nullable();
            $table->date('submit_date')->nullable();
            $table->date('test_date')->nullable();
            $table->date('return_date')->nullable();
            $table->string('venue')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('interval')->nullable();
            $table->integer('applicants_interval')->nullable();
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
        Schema::dropIfExists('plms_blood_tests');
    }
}
