<?php

namespace App\Imports;

use App\Models\AcademicYear;
use App\Models\TeacherSubject;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithUpserts;

class TeacherSubjectImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new TeacherSubjectSheetImport(),
        ];
    }
}
