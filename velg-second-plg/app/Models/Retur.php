<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retur extends Model
{
    use HasFactory;

    // Jika nama table default "returs", ini bisa diabaikan.
    protected $table = 'returs';

    protected $fillable = [
        'nomor',
        'tanggal',
        'customer_id',
        'transaksi_id',
        'transaksi_kode',
        'total',
        'status',
        'keterangan',
        'bukti_files',
    ];

    protected $casts = [
        'tanggal'      => 'date',
        'total'        => 'decimal:2',
        'bukti_files'  => 'array',
    ];

    public function pelanggan()
    {
        // FIX: the FK is 'customer_id' in your migration
        return $this->belongsTo(\App\Models\Pelanggan::class, 'customer_id');
    }

    public function transaksi()
    {
        return $this->belongsTo(\App\Models\Transaksi::class, 'transaksi_id');
    }
}
