<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    // Nama tabel default akan "pembayarans" (plural dari model). Set manual jika berbeda:
    // protected $table = 'pembayarans';

    protected $fillable = [
        'order_id',
        'nama',
        'jumlah',
        'metode',
        'tanggal',
        'bukti',
        'status',
        'user_id',
        'items',
    ];

    protected $casts = [
        'order_id' => 'integer',
        'jumlah'   => 'decimal:2',
        'tanggal'  => 'date',
        'items'    => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
