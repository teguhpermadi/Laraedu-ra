<?php

namespace App\Imports;

use App\Models\AcademicYear;
use App\Models\TeacherSubject;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;

class TeacherSubjectSheetImport implements ToModel, WithHeadingRow, WithUpserts
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $academic = AcademicYear::active()->first()->id;

        return new TeacherSubject([
            'academic_year_id' => $academic,
            'teacher_id' => $row['teacher_id'],
            'grade_id' => $row['grade_id'],
            'subject_id' => $row['subject_id'],
        ]);
    }

    /**
     * @return string|array
     */
    public function uniqueBy()
    {
        return [
            'academic_year_id',
            'grade_id',
            'teacher_id',
            'subject_id',
        ];
    }
}
