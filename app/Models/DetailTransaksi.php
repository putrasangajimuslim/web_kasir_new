<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailTransaksi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'detail_transaksi';

    protected $fillable = [
        'transaksi_id',
        'kode_barang',
        'jumlah',
        'harga_jual',
        'subtotal_item',
        'keuntungan',
    ];

    public function products() {
        return $this->belongsTo(Products::class, 'barang_id', 'id');
    }

    public function transaksi() {
        return $this->belongsTo(Transaksi::class, 'transaksi_id', 'id');
    }
}
