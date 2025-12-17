<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('nama', 255);
            $table->decimal('jumlah', 15, 2);
            $table->string('metode', 100);
            $table->date('tanggal');
            $table->string('bukti')->nullable(); // path file bukti di storage
            $table->enum('status', ['pending', 'terverifikasi', 'gagal'])->nullable();
            $table->index(['order_id', 'tanggal']);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};