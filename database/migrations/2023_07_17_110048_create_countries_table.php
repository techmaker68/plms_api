<?php
// ***********************************
// @author Syed, Aqsa, Saqib
// @create_date 21-07-2023
// ***********************************
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->unsignedSmallInteger('id')->comment('ISO 3166-1 Numeric code');
            $table->char('country_code_2', 2)->nullable();
            $table->char('country_code_3', 3)->nullable();
            $table->unsignedSmallInteger('region_code')->nullable();
            $table->unsignedSmallInteger('subregion_code')->nullable();
            $table->unsignedSmallInteger('intermediate_region_code')->nullable();
            $table->string('country_name_short_en', 64)->nullable();
            $table->string('country_name_full_en', 150)->nullable();
            $table->string('country_name_short_local', 64)->nullable();
            $table->string('country_name_full_local', 150)->nullable();
            $table->string('country_name_short_zh_cn', 64)->nullable();
            $table->string('country_name_full_zh_cn', 150)->nullable();
            $table->string('country_name_short_ar', 96)->nullable();
            $table->string('country_name_full_ar', 150)->nullable();
            $table->string('nationality_en', 48)->nullable();
            $table->string('nationality_zh_cn', 48)->nullable();
            $table->string('nationality_ar', 48)->nullable();
            $table->timestamps();
            
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }
}
