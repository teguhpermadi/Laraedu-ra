<?php

namespace App\Imports;

use App\Models\Subject;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SubjectImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Subject([
            'name' => $row['nama_mata_pelajaran'],
            'code' => $row['kode_mata_pelajaran'],
            'order' => $row['urutan_mata_pelajaran'],
        ]);
    }
}
