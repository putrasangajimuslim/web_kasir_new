<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'barang';

    protected $fillable = [
        'kode_barang',
        'kategori_id',
        'nama_barang',
        'merk',
        'harga_beli',
        'harga_jual',
        'margin_keuntungan',
        'satuan_barang',
        'stok',
    ];

    public function kategori()
    {
        return $this->belongsTo(Categories::class, 'kategori_id');
    }
}
