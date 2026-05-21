<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;

    protected $table = 'dokter';
    protected $primaryKey = 'id_dokter';

    protected $fillable = [
        'id_user',
        'nama_dokter',
        'spesialisasi',
        'no_telp',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function resep()
    {
        return $this->hasMany(Resep::class, 'id_dokter');
    }
}