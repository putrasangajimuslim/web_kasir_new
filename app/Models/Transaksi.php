<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaksi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transaksi';

    protected $fillable = [
        'tgl_transaksi',
        'subtotal',
        'kasir_id',
    ];

    public function kasir() {
        return $this->belongsTo(User::class, 'kasir_id', 'id');
    }
}
