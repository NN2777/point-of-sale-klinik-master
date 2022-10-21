<?php

namespace App\Exports;

use App\Models\Produk;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportProduk implements FromArray, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct(array $invoices)
    {
        $this->invoices = $invoices;
    }

    public function array(): array
    {
        return $this->invoices;
    }

    public function headings(): array
    {
        return ["id_produk", "kategori", "kode_produk", "nama_produk", "merk", "harga_beli", "harga_jual_1", "harga_jual_2", "harga_jual_3", "harga_jual_4", "stok", "diskon"];
    }
}
