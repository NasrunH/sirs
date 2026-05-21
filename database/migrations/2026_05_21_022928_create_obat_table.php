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
        Schema::create('obat', function (Blueprint $table) {
            $table->id('id_obat');
            $table->string('kode_obat', 20)->unique();
            $table->string('nama_obat', 100);
            $table->string('kategori', 50);
            $table->integer('stok')->default(0);
            $table->decimal('harga', 15, 2); // 15 digit total, 2 desimal
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('obat');
    }
};
