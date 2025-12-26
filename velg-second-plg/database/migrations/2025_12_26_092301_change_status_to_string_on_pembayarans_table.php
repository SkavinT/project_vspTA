<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE pembayarans
            MODIFY status ENUM(
                'proses verifikasi',
                'diverifikasi',
                'dikemas',
                'sedang dalam perjalanan',
                'terkirim',
                'dibatalkan'
            ) NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE pembayarans
            MODIFY status ENUM('pending','terverifikasi','gagal') NULL
        ");
    }
};
