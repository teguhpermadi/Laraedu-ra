<?php

namespace App\Filament\Resources\TeacherSubjectResource\Pages;

use App\Filament\Resources\TeacherSubjectResource;
use App\Models\AcademicYear;
use App\Models\TeacherSubject;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateTeacherSubject extends CreateRecord
{
    protected static string $resource = TeacherSubjectResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $teacherSubject = [];
        foreach ($data['grade_id'] as $grade_id) {
            $teacherSubject = [
                'academic_year_id' => AcademicYear::active()->first()->id,
                'teacher_id' => $data['teacher_id'],
                'subject_id' => $data['subject_id'],
                'grade_id' => $grade_id,
                'passing_grade' => $data['passing_grade'],
            ];
            
            static::getModel()::updateOrCreate($teacherSubject);
            // dd($teacherSubject);
        }

        return static::getModel()::updateOrCreate($teacherSubject);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
