<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpsertColumns;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithProgressBar;

class StudentImport implements ToModel, WithHeadingRow, WithUpserts, WithUpsertColumns, WithProgressBar
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Student([
            'nisn' => $row['nisn'],
            'nis' => $row['nis'],
            'name' => $row['nama_lengkap'],
            'gender' => Str::lower($row['jenis_kelamin']),
        ]);
    }

    public function uniqueBy()
    {
        return ['nisn', 'nis'];
    }

    public function upsertColumns()
    {
        return ['name', 'gender'];
    }
}
