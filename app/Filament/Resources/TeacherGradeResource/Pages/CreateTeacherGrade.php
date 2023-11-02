<?php

namespace App\Filament\Resources\TeacherGradeResource\Pages;

use App\Filament\Resources\TeacherGradeResource;
use App\Models\Userable;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTeacherGrade extends CreateRecord
{
    protected static string $resource = TeacherGradeResource::class;

    protected function afterCreate(): void
    {
        // Runs after the form fields are saved to the database.
        $teacher_id = $this->getRecord()->teacher_id;
        $userable = Userable::with('user')->where('user_id', $teacher_id)->first();

        $userable->user->assignRole('teacher grade');
    }
}
