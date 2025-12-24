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
        'total',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'total'   => 'decimal:2',
    ];

    public function pelanggan()
    {
        // FIX: the FK is 'customer_id' in your migration
        return $this->belongsTo(\App\Models\Pelanggan::class, 'customer_id');
    }
}
