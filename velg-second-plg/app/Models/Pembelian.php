<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    // Jika nama tabel default "pembelians", ini bisa diabaikan.
    // protected $table = 'pembelians';

    protected $fillable = [
        'tanggal',
        'supplier_id',
        'total',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'total' => 'decimal:2',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
