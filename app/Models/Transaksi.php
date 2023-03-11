<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'outlet_id',
        'member_id',
        'user_id',
        'kode_invoice',
        'tgl',
        'batas_waktu',
        'tgl_bayar',
        'biaya_tambahan',
        'diskon',
        'pajak',
        'sub_total',
        'qty_total',
        'total_bayar',
        'cash',
        'kembalian',
        'status',
        'dibayar'
    ];
}
