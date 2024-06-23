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
        Schema::create('stok_barang_jurnal_harians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jurnal_harian_id')->constrained('jurnal_harians');
            $table->foreignId('bahan_setengah_jadi_id')->constrained('bahan_setengah_jadis');
            $table->string('lokasi');
            $table->string('qty');
            $table->string('minus')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_barang_jurnal_harians');
    }
};