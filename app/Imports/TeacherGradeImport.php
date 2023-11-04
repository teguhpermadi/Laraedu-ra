<?php

namespace App\Imports;

use App\Models\AcademicYear;
use App\Models\TeacherGrade;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithUpsertColumns;
use Maatwebsite\Excel\Concerns\WithUpserts;

class TeacherGradeImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new TeacherGradeSheetImport(),
        ];
    }
}
