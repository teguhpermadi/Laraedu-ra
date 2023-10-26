<?php

namespace App\Filament\Resources\StudentGradeResource\Pages;

use App\Filament\Resources\StudentGradeResource;
use App\Models\AcademicYear;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateStudentGrade extends CreateRecord
{
    protected static string $resource = StudentGradeResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $student_grade = [];
        foreach ($data['student_ids'] as $student_id) {
            $student_grade = [
                'academic_year_id' => AcademicYear::active()->first()->id,
                'grade_id' => $data['grade_id'],
                'student_id' => $student_id,
            ];

            static::getModel()::updateOrCreate($student_grade);
        }
        // dd($student_grade);
        return static::getModel()::updateOrCreate($student_grade);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
