<?php

namespace App\Imports;

use App\Models\AcademicYear;
use App\Models\StudentGrade;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpsertColumns;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class StudentGradeImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new StudentGradeSheetImport(),
        ];
    }
}
