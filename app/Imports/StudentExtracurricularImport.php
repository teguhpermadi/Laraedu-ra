<?php

namespace App\Imports;

use App\Models\AcademicYear;
use App\Models\StudentExtracurricular;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class StudentExtracurricularImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new StudentExtracurricularSheetImport(),
        ];
    }
}
