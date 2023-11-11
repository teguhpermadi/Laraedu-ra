<?php

namespace App\Filament\Resources\StudentExtracurricularResource\Pages;

use App\Filament\Resources\StudentExtracurricularResource;
use App\Models\AcademicYear;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateStudentExtracurricular extends CreateRecord
{
    protected static string $resource = StudentExtracurricularResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $student_extracurricular = [];
        foreach ($data['student_ids'] as $student_id) {
            $student_extracurricular = [
                'academic_year_id' => AcademicYear::active()->first()->id,
                'extracurricular_id' => $data['extracurricular_id'],
                'student_id' => $student_id,
            ];

            static::getModel()::updateOrCreate($student_extracurricular);
        }
        // dd($student_extracurricular);
        return static::getModel()::updateOrCreate($student_extracurricular);
    }
}
