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
        Schema::create('salesbyitems', function (Blueprint $table) {
            $table->id();
            $table->integer('id_from_sales');
            $table->unsignedBigInteger('branch_id');
            $table->integer('omega_id');
            $table->integer('invoicenumber'); 
            $table->integer('closed');
            $table->date('eoddate');
            $table->decimal('qty', 7, 3);
            $table->decimal('totalprice', 7, 3);
            $table->string('item_desc');
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
        Schema::dropIfExists('salesbyitems');
    }
};
