<?php
// ***********************************
// @author Syed, Umair
// @create_date 21-07-2023
// ***********************************
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlmsLoisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plms_lois', function (Blueprint $table) {
            $table->id();
            $table->integer('batch_no')->unique()->nullable();
            $table->integer('nation_category')->nullable();
            $table->integer('loi_type')->comment('1= 3 Months , 2 = 6 Months , 3 = 12 Months')->nullable();
            $table->date('submission_date')->nullable();
            $table->foreignId('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('plms_companies')->onDelete('cascade');
            $table->string('company_address_iraq_ar')->nullable();
            $table->string('entry_purpose')->nullable();
            $table->string('entry_type')->nullable();
            $table->date('contract_expiry')->nullable();
            $table->string('company_address_ar')->nullable();
            $table->date('mfd_date')->nullable();
            $table->string('mfd_ref')->nullable();
            $table->date('hq_date')->nullable();
            $table->string('hq_ref')->nullable();
            $table->date('boc_moo_date')->nullable();
            $table->string('boc_moo_ref')->nullable();
            $table->date('moo_date')->nullable();
            $table->string('moo_ref')->nullable();
            $table->date('moi_date')->nullable();
            $table->string('moi_ref')->nullable();
            $table->date('moi_2_date')->nullable();
            $table->string('moi_2_ref')->nullable();
            $table->date('majnoon_date')->nullable();
            $table->string('majnoon_ref')->nullable();
            $table->date('moi_payment_date')->nullable();
            $table->text('loi_photo_copy')->nullable();
            $table->text('payment_copy')->nullable();
            $table->text('mfd_copy')->nullable(); 
            $table->text('hq_copy')->nullable(); 
            $table->text('boc_moo_copy')->nullable(); 
            $table->string('moi_invoice')->nullable();
            $table->string('moi_deposit')->nullable();
            $table->date('loi_issue_date')->nullable();
            $table->integer('loi_no')->nullable();
            $table->date('sent_loi_date')->nullable();
            $table->date('close_date')->nullable();
            $table->tinyInteger('priority')->comment('1 = low , 2 = medium, 3 = high')->nullable();
            $table->date('mfd_submit_date')->nullable();
            $table->date('mfd_received_date')->nullable();
            $table->date('hq_submit_date')->nullable();
            $table->date('hq_received_date')->nullable();
            $table->date('boc_moo_submit_date')->nullable();
            $table->date('moi_payment_letter_date')->nullable();
            $table->string('moi_payment_letter_ref')->nullable();
            $table->text('expected_issue_date')->nullable();
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
        Schema::dropIfExists('plms_lois');
    }
}
