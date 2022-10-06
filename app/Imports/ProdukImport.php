<?php

namespace App\Imports;

use App\Models\Produk;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProdukImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Produk([
            "kode_produk" => $row['kode_produk'],
            "nama_produk" => $row['nama_produk'],
            "id_kategori" => $row['id_kategori'],
            "merk" => $row['merk'],
            "harga_beli" => $row['harga_beli'],
            "diskon" => $row['diskon'],
            "harga_jual_1" => $row['harga_jual_1'],
            "harga_jual_2" => $row['harga_jual_2'],
            "harga_jual_3" => $row['harga_jual_3'],
            "harga_jual_4" => $row['harga_jual_4'],
            "stok" => $row['stok']
        ]);
    }
}
