<?php
// ***********************************
// @author Syed, Aqsa, Saqib
// @create_date 21-07-2023
// ***********************************
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlmsPassportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plms_passports', function (Blueprint $table) {
            $table->id();
            $table->integer('pax_id')->nullable();
            $table->foreign('pax_id')->references('pax_id')->on('plms_paxes')->onDelete('cascade');
            $table->string('full_name')->nullable();
            $table->string('arab_full_name')->nullable();
            $table->string('passport_no')->nullable();
            $table->string('type')->nullable();
            $table->date('date_of_issue')->nullable();
            $table->date('date_of_expiry')->nullable();
            $table->date('birthday')->nullable();
            $table->integer('passport_country')->nullable();
            $table->integer('place_of_issue')->nullable();
            $table->tinyInteger('status')->comment('1= active, 2= expired , 3=to be renewed , 4=cancelled')->default(1);
            $table->text('file')->nullable();
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
        Schema::dropIfExists('plms_passports');
    }
}
