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
        // Model TukarTambah menggunakan table 'tukar_tambah'
        Schema::create('tukar_tambah', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name', 255);
            $table->string('phone', 50)->nullable();
            $table->string('item_old', 255);
            $table->string('item_new', 255);
            $table->decimal('price', 15, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Index opsional bila sering dicari
            $table->index(['customer_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tukar_tambah');
    }
};