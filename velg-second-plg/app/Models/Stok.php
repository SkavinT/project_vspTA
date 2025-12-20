<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    use HasFactory;

    // If your table name is not the default "stoks", uncomment and adjust:
    // protected $table = 'stoks';

    protected $fillable = [
        'produk_id',
        'nama',
        'kategori',
        'jumlah',
        'harga',
        'keterangan',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'harga' => 'decimal:2',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
