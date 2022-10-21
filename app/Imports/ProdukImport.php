<?php

namespace App\Imports;

use App\Models\Kategori;
use App\Models\Produk;
use Maatwebsite\Excel\Concerns\ToModel;

class ProdukImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Produk([
            'id_kategori' => Kategori::where('nama_kategori', $row['0'])->firstOrFail()->id_kategori,
            'kode_produk'=> $row[1],
            'nama_produk'       => $row[2],
            'merk'     => $row[3],
            'harga_beli'    => $row[4],
            'harga_jual_1'    => $row[5],
            'harga_jual_2'    => $row[6],
            'harga_jual_3'    => $row[7],
            'harga_jual_4'    => $row[8],
            'stok'    => $row[9],
            'diskon'    => $row[10] ?? 0,

        ]);
    }
}
