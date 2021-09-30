<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transaksi extends Model
{
    use HasFactory;
    protected $fillable = ['tanggal_transaksi', 'kode_barang', 'nama_barang', 'kuantitas', 'user_id', 'created_at', 'tipe_transaksi', 'alamat', 'is_confirmed'];
}
