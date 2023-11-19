<?php

namespace App\Imports;

use App\Models\AcademicYear;
use App\Models\Teacher;
use App\Models\TeacherGrade;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpsertColumns;
use Maatwebsite\Excel\Concerns\WithUpserts;

class TeacherGradeSheetImport implements ToModel, WithHeadingRow, WithUpserts, WithUpsertColumns
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $academic = AcademicYear::active()->first()->id;

        // give roles Teacher Grade
        $teacher_id = $row['teacher_id'];
        $teacher = Teacher::find($teacher_id);
        
        $user = $teacher->userable->user->assignRole('teacher_grade');
        
        return new TeacherGrade([
            'academic_year_id' => $academic,
            'teacher_id' => $row['teacher_id'],
            'grade_id' => $row['grade_id'],
        ]);

    }
    

    /**
     * @return string|array
     */
    public function uniqueBy()
    {
        return [
            'academic_year_id',
            'teacher_id',
            'grade_id',
        ];
    }

    public function upsertColumns()
    {
        return [
            'academic_year_id',
            'teacher_id',
            'grade_id',
        ];
    }
}
