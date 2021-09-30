<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class barang extends Model
{
    use HasFactory;
    protected $fillable = ['kode_barang', 'nama_barang', 'kuantitas', 'nama_toko', 'user_id'];
}
