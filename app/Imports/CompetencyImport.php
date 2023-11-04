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

class CompetencyImport implements ToCollection, WithHeadingRow
{
    use Importable;

    public function collection(Collection $rows)
    {
        $data = [];

        foreach ($rows as $row) 
        {
            $data = [
                'code' => $row['code'],
                'description' => $row['description'],
                'passing_grade' => $row['passing_grade'],
            ];
        }

        return $data;
    }
}
