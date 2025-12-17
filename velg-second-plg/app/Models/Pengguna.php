<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengguna extends Model
{
    use HasFactory;

    protected $table = 'penggunas';

    protected $fillable = [
        'nama',
        'email',
        'password',
        // tambah kolom lain jika ada, mis: 'alamat', 'telepon'
    ];

    protected $hidden = [
        'password',
    ];
}
