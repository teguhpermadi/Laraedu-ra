<?php

namespace App\Imports;

use App\Models\AcademicYear;
use App\Models\StudentExtracurricular;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentExtracurricularSheetImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        $academic = AcademicYear::active()->first()->id;

        foreach ($rows as $row) 
        {
            StudentExtracurricular::updateOrCreate([
                'academic_year_id' => $academic,
                'extracurricular_id' => $row['extracurricular_id'],
                'student_id' => $row['student_id'],
            ]);
        }
    }
}
