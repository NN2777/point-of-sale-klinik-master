<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelian';
    protected $primaryKey = 'id_pembelian';
    protected $guarded = [];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier', 'id_supplier');
    }

    public function pembelian_detail()
    {
        return $this->hasMany(PembelianDetail::class, 'id_pembelian_detail', 'id_pembelian_detail');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'id_pembayaran', 'id_pembayaran');
    }
}
