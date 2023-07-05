<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('void_refunds', function (Blueprint $table) {
            $table->id();
            $table->integer('id_from_sales');
            $table->unsignedBigInteger('branch_id');
            $table->integer('omega_id');
            $table->string('cust_name');
            $table->date('eoddate');
            $table->decimal('qty', 7, 3);
            $table->decimal('totalprice', 7, 3);
            $table->integer('invoicenumber'); 
            $table->string('item_desc');
            $table->string('employee');
            $table->timestamps();

            
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('void_refunds');
    }
};
