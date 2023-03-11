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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outlet_id')->contrained('outlets')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('member_id')->contrained('members')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('user_id')->contrained('users')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->string('kode_invoice')->unique();
            $table->dateTime('tgl');
            $table->dateTime('batas_waktu');
            $table->dateTime('tgl_bayar')->nullable();
            $table->integer('biaya_tambahan')->nullable();
            $table->integer('diskon')->nullable();
            $table->integer('pajak');
            $table->integer('sub_total');
            $table->integer('qty_total');
            $table->integer('total_bayar');
            $table->integer('cash')->nullable();
            $table->integer('kembalian')->nullable();
            $table->enum('status',['baru','proses','selesai','diambil','batal']);
            $table->enum('dibayar',['dibayar','belum_bayar','dikembalikan']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksis');
    }
};
