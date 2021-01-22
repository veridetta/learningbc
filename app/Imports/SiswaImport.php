<?php

namespace App\Imports;

use App\Models\SiswaModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new SiswaModel([
            //
            'nis' => $row['nis'],
            'nama' => $row['nama'], 
            'kelas_induk' => $row['kelas_induk'], 
            'kelas'=> $row['kelas']
        ]);
    }
}
