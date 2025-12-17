<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    ];

    // Casting untuk kolom tertentu
    protected $casts = [
        'price' => 'decimal:2',
    ];
}
