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
        Schema::table('pembelians', function (Blueprint $table) {
            $table->foreignId('product_id')->nullable()->constrained('produks')->nullOnDelete();
            $table->string('gambar')->nullable();
            $table->decimal('harga_modal', 15, 2)->default(0);
            $table->unsignedInteger('jumlah')->default(1);
            // keep existing 'total' and 'keterangan'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembelians', function (Blueprint $table) {
            $table->dropConstrainedForeignId('product_id');
            $table->dropColumn(['gambar','harga_modal','jumlah']);
        });
    }
};
