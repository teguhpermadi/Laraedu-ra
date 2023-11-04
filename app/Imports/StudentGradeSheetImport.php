<?php

namespace App\Imports;

use App\Models\AcademicYear;
use App\Models\StudentGrade;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpsertColumns;
use Maatwebsite\Excel\Concerns\WithUpserts;

class StudentGradeSheetImport implements ToModel, WithHeadingRow, WithUpserts, WithUpsertColumns
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $academic = AcademicYear::active()->first()->id;

        return new StudentGrade([
            'academic_year_id' => $academic,
            'grade_id' => $row['grade_id'],
            'student_id' => $row['student_id'],
        ]);
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
