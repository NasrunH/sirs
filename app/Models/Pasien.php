<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;

    protected $table = 'pasien';
    protected $primaryKey = 'id_pasien';

    protected $fillable = [
        'id_user',
        'no_rekam_medis',
        'nama_lengkap',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
    ];

    // Relasi ke user (opsional, jika pasien punya akun login)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Satu pasien bisa memiliki banyak riwayat resep
    public function resep()
    {
        return $this->hasMany(Resep::class, 'id_pasien');
    }
}