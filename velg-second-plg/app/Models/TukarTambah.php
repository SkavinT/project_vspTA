<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Produk;

class TukarTambah extends Model
{
    use HasFactory;

    // Sesuaikan jika nama tabel di database adalah "tukar_tambah"
    protected $table = 'tukar_tambah';

    // Kolom yang dapat diisi mass-assignment (sesuai controller)
    protected $fillable = [
        'customer_name',
        'phone',
        'item_old',
        'item_new',
        'price',
        'notes',
        'user_id',
        'produk_id',
        'condition_image',
        'status',          // <— tambah ini
    ];

    // Casting untuk kolom tertentu
    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
