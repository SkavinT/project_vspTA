<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $fillable = [
        'tanggal',
        'customer_name',
        'product_id',
        'quantity',
        'price',
        'total',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'product_id' => 'integer',
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // Relasi sesuai validasi controller: exists:products,id
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'product_id');
    }
}
