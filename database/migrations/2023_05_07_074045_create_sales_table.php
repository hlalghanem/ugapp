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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->integer('id_from_sales');
            $table->unsignedBigInteger('branch_id');
            $table->integer('omega_id');
            $table->string('cust_name');
            $table->integer('invoicenumber');
            $table->integer('invbranch');
            $table->integer('custnb');
            $table->decimal('amount', 7, 3);
            $table->decimal('discount', 7, 3);
            $table->decimal('total', 7, 3);
            $table->integer('wrkstid');           
            $table->date('eoddate');
            $table->integer('closed'); 
            $table->integer('nottoday'); 
            $table->string('menu');
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
        Schema::dropIfExists('sales');
    }
};
