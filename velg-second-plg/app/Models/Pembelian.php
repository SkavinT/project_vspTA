<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'supplier_id',
        'product_id',
        'gambar',
        'harga_modal',
        'jumlah',
        'total',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'harga_modal' => 'decimal:2',
        'total' => 'decimal:2',
        'jumlah' => 'integer',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function produk()
    {
        return $this->belongsTo(\App\Models\Produk::class, 'product_id');
    }
}
