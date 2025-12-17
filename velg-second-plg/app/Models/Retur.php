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
        return $this->belongsTo(Pelanggan::class);
    }
}
