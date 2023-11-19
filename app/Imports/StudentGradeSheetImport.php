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

class StudentGradeSheetImport implements ToCollection, WithHeadingRow, WithUpserts, WithUpsertColumns
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        $academic = AcademicYear::active()->first()->id;

        foreach ($rows as $row) 
        {
            StudentGrade::updateOrCreate([
                'academic_year_id' => $academic,
                'grade_id' => $row['grade_id'],
                'student_id' => $row['student_id'],
            ]);
        }
    }

    /**
     * @return string|array
     */
    public function uniqueBy()
    {
        return [
            'academic_year_id',
            'student_id',
            'grade_id',
        ];
    }

    public function upsertColumns()
    {
        return [
            'academic_year_id',
            'student_id',
            'grade_id',
        ];
    }
}
