<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\NilaiModel;

class NilaiImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new NilaiModel([
            //
            'nis' => $row['nis'],
            'mapel' => $row['mapel'], 
            'nama_tes' => $row['nama_tes'], 
            'nilai' => $row['nilai'], 
            'kelas'=> $row['kelas']
        ]);
    }
}
