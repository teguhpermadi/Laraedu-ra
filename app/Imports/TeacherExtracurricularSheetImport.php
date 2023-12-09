<?php

namespace App\Imports;

use App\Models\AcademicYear;
use App\Models\Teacher;
use App\Models\TeacherExtracurricular;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TeacherExtracurricularSheetImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        $academic = AcademicYear::active()->first()->id;

        foreach ($rows as $row) 
        {
            TeacherExtracurricular::updateOrCreate([
                'academic_year_id' => $academic,
                'extracurricular_id' => $row['extracurricular_id'],
                'teacher_id' => $row['teacher_id'],
            ]);

            $teacher = Teacher::find($row['teacher_id']);
            $teacher->userable->user->assignRole('teacher_extracurricular');
            // $teacher->userable->user->givePermissionTo(
            //     'assesment_student::extracurricular',
            //     'view_any_student::extracurricular',
            // );
        }
    }
}
