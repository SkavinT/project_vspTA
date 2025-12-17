<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanTransaksi extends Model
{
    protected $fillable = [
        'tanggal',
        'total',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'total' => 'decimal:2',
    ];
}