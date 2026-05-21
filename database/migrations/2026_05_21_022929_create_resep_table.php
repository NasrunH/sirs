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
        Schema::create('resep', function (Blueprint $table) {
            $table->id('id_resep');
            $table->date('tanggal_resep');
            $table->foreignId('id_pasien')->constrained('pasien', 'id_pasien');
            $table->foreignId('id_dokter')->constrained('dokter', 'id_dokter');
            $table->foreignId('id_user')->comment('User admin yang kelola')->constrained('users', 'id_user');
            $table->decimal('total_harga', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resep');
    }
};
