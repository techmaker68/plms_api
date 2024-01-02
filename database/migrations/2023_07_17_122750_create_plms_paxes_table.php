<?php
// ***********************************
// @author Syed, Aqsa, Saqib
// @create_date 21-07-2023
// ***********************************
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlmsPaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plms_paxes', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->integer('pax_id')->unique()->nullable();
            $table->foreignId('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('plms_companies')->onDelete('cascade');
            $table->foreignId('department_id')->nullable();
            $table->foreign('department_id')->references('id')->on('plms_departments')->onDelete('cascade');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('employee_no')->nullable();
            $table->string('eng_full_name')->nullable();
            $table->string('arab_full_name')->nullable();
            $table->integer('nationality')->nullable();
            $table->string('arab_position')->nullable();
            $table->integer('country_residence')->nullable();
            $table->string('position')->nullable();
            $table->string('dob')->nullable();
            $table->integer('country_code')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('gender')->nullable();
            $table->integer('badge_no')->nullable();
            $table->tinyInteger('status')->comment('1= onboard, 2= offboard')->default(1);
            $table->date('offboard_date')->nullable();
            $table->text('image')->nullable();
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
        Schema::dropIfExists('plms_paxes');
    }
}
