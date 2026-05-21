<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;

    protected $table = 'obat';
    protected $primaryKey = 'id_obat';

    protected $fillable = [
        'kode_obat',
        'nama_obat',
        'kategori',
        'stok',
        'harga',
    ];

    public function detailResep()
    {
        return $this->hasMany(DetailResep::class, 'id_obat');
    }
}