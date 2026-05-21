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
        Schema::create('detail_resep', function (Blueprint $table) {
            $table->id('id_detail');
            $table->foreignId('id_resep')->constrained('resep', 'id_resep')->cascadeOnDelete();
            $table->foreignId('id_obat')->constrained('obat', 'id_obat');
            $table->integer('jumlah');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->string('aturan_pakai', 50);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_resep');
    }

};
