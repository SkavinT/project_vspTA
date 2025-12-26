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
        if (!Schema::hasColumn('stoks', 'produk_id')) {
            Schema::table('stoks', function (Blueprint $table) {
                $table->foreignId('produk_id')
                    ->after('id')
                    ->constrained('produks')
                    ->cascadeOnUpdate()
                    ->restrictOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('stoks', 'produk_id')) {
            Schema::table('stoks', function (Blueprint $table) {
                $table->dropConstrainedForeignId('produk_id');
            });
        }
    }
};
