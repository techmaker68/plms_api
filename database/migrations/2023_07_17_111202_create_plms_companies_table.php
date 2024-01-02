<?php
// ***********************************
// @author Syed, Aqsa, Saqib
// @create_date 21-07-2023
// ***********************************
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlmsCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plms_companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->string('short_name')->nullable();
            $table->string('industry')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->text('address_1')->nullable();
            $table->string('city')->nullable();
            $table->integer('country_id')->nullable();
            $table->string('poc_name')->nullable();
            $table->string('poc_email_or_username')->nullable();
            $table->string('poc_mobile')->nullable();
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
        Schema::dropIfExists('plms_companies');
    }
}
