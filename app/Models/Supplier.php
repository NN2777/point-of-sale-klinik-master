<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'supplier';
    protected $primaryKey = 'id_supplier';
    protected $guarded = [];

    public function pembelian()
    {
        return $this->hasMany(Pembelian::class, 'id_pembelian', 'id_pembelian');
    }

    public function pembelian_detail(){
        return $this->hasManyThrough(PembelianDetail::class, Pembelian::class );
    }
}
