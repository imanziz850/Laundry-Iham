<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'transaksi_id',
        'paket_id',
        'harga',
        'diskon_paket',
        'qty',
        'sub_total',
        'keterangan',
    ];
}
