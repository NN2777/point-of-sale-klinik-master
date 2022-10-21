<?php

namespace App\Exports;

use App\Models\Dokter;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportDokter implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Dokter::all();
    }

    public function headings(): array
    {
        return ["id_dokter", "kode_dokter", "nama", "alamat", "telpon", "created_at", "updated_at"];
    }
}
