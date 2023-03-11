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
        Schema::create('transaksi_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->constrained('transaksis')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('paket_id')->constrained('pakets')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->integer('harga');
            $table->integer('diskon_paket')->nullable();
            $table->integer('qty');
            $table->integer('sub_total');
            $table->string('keterangan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi_details');
    }
};
