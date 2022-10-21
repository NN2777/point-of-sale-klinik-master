<?php

namespace App\Imports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\ToModel;

class MemberImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Member([
            'kode_member'=> $row[0],
            'nama'       => $row[1],
            'alamat'     => $row[2],
            'telepon'    => $row[3],
        ]);
    }
}
