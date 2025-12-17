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
        Schema::create('returs', function (Blueprint $table) {
            $table->id();
            $table->string('nomor', 50)->unique();
            $table->date('tanggal');
            $table->foreignId('customer_id')->nullable()->constrained('pelanggans')->nullOnDelete();
            $table->decimal('total', 15, 2);
            $table->enum('status', ['pending', 'approved', 'rejected']);
            $table->text('keterangan')->nullable();
            $table->index(['tanggal', 'status']);
            $table->timestamps();

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returs');
    }
};