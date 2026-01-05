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
        Schema::table('tukar_tambah', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->index()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreignId('produk_id')
                ->nullable()
                ->index()
                ->constrained('produks')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->string('condition_image')->nullable()->after('item_new');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tukar_tambah', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['produk_id']);
            $table->dropColumn(['user_id','produk_id','condition_image']);
        });
    }
};
