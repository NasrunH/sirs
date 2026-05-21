<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailResep extends Model
{
    use HasFactory;

    protected $table = 'detail_resep';
    protected $primaryKey = 'id_detail';

    protected $fillable = [
        'id_resep',
        'id_obat',
        'jumlah',
        'harga_satuan',
        'subtotal',
        'aturan_pakai',
    ];

    public function resep()
    {
        return $this->belongsTo(Resep::class, 'id_resep');
    }

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat');
    }
}