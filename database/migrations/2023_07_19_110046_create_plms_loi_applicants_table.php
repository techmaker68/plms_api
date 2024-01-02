<?php
// ***********************************
// @author Syed, Umair
// @create_date 21-07-2023
// ***********************************
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlmsLoiApplicantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plms_loi_applicants', function (Blueprint $table) {
            $table->id();
            $table->integer('pax_id')->nullable();
            $table->foreign('pax_id')->references('pax_id')->on('plms_paxes')->onDelete('cascade');
            $table->integer('status')->comment('0 = approved , 1 rejected , 2 cancelled , 3 give up ')->nullable();
            $table->integer('batch_no')->nullable(); 
            $table->foreign('batch_no')->references('batch_no')->on('plms_lois')->onDelete('cascade');
            $table->date('loi_payment_date')->nullable();
            $table->date('loi_issue_date')->nullable();
            $table->integer('deposit_amount')->nullable();
            $table->integer('loi_payment_receipt_no')->nullable();
            $table->integer('sequence_no')->nullable()->default(0);
            $table->text('remarks')->nullable();
            $table->string('full_name')->nullable();
            $table->string('arab_full_name')->nullable();
            $table->string('passport_no')->nullable();
            $table->integer('nationality')->nullable();
            $table->string('department')->nullable();
            $table->string('employer')->nullable();
            $table->string('position')->nullable();
            $table->string('pax_type')->nullable();
            $table->string('email')->nullable();
            $table->string('arab_position')->nullable();
            $table->integer('country_residence')->nullable();
            $table->text('payment_letter_copy')->nullable();
            $table->text('loi_photo_copy')->nullable();
            $table->integer('loi_no')->nullable();
            $table->string('phone')->nullable();
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
        Schema::dropIfExists('plms_loi_applicants');
    }
}
