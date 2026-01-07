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
        Schema::table('returs', function (Blueprint $table) {
            $table->foreignId('transaksi_id')
                ->nullable()
                ->after('customer_id')
                ->constrained('transaksis')
                ->nullOnDelete();

            $table->string('transaksi_kode', 50)
                ->nullable()
                ->after('transaksi_id')
                ->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('returs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('transaksi_id');
            $table->dropColumn('transaksi_kode');
        });
    }
};
