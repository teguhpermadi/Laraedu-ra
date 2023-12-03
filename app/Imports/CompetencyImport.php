<?php

namespace App\Imports;

use App\Models\Competency;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\WithUpsertColumns;
use Maatwebsite\Excel\Concerns\WithUpserts;

class CompetencyImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if(!is_null($row['kode']) && !is_null($row['deskripsi']) && !is_null($row['kkm'])){
                Competency::updateOrCreate([
                    'code' => $row['kode'],
                ],[
                    'teacher_subject_id' => $row['teacher_subject_id'],
                    'code' => $row['kode'],
                    'description' => $row['deskripsi'],
                    'passing_grade' => $row['kkm'],
                ]);
            }
        }
    }

    public function headingRow(): int
    {
        return 10;
    }    
}
