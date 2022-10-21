<?php

namespace App\Imports;

use App\Models\Dokter;
use Maatwebsite\Excel\Concerns\ToModel;

class DokterImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Dokter([
            'kode_dokter'=> $row[0],
            'nama'       => $row[1],
            'alamat'     => $row[2],
            'telepon'     => $row[3],
        ]);
    }
}
