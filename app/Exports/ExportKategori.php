<?php

namespace App\Exports;

use App\Models\Kategori;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportKategori implements FromCollection, WithHeadings 
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function collection()
    {
        return Kategori::all();
    }

    public function headings(): array
    {
        return ["id", "nama_kateogri", "created_at", "updated_at"];
    }
//     protected $invoices;

//     public function __construct(array $invoices)
//     {
//         $this->invoices = $invoices;
//     }

//     public function array(): array
//     {
//         return $this->invoices;
//     }

//     public function headings(): array
//     {
//         return [
//             'id',
//             'kategori',
//         ];
//     }
}
