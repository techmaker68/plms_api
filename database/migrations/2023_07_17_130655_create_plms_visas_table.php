<?php
// ***********************************
// @author Syed, Aqsa, Saqib
// @create_date 21-07-2023
// ***********************************
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlmsVisasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plms_visas', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->nullable();             
            $table->integer('pax_id')->nullable();
            $table->foreign('pax_id')->references('pax_id')->on('plms_paxes')->onDelete('cascade');
            $table->integer('loi_id')->nullable();
            $table->foreign('loi_id')->references('id')->on('plms_lois')->onDelete('cascade');
            $table->integer('passport_id')->nullable();
            $table->foreign('passport_id')->references('id')->on('plms_passports')->onDelete('cascade');
            $table->string('type')->nullable();
            $table->date('date_of_issue')->nullable();
            $table->date('date_of_expiry')->nullable();
            $table->integer('badge_no')->nullable();
            $table->string('visa_no')->comment('visa sticker in front end')->nullable(); // visa sticker in front end
            $table->string('visa_location')->nullable();
            $table->tinyInteger('status')->comment('1= active, 2= expired , 3/4=to be renewed')->default(0); 
            $table->text('file')->nullable();
            $table->text('reason')->nullable();
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
        Schema::dropIfExists('plms_visas');
    }
}
