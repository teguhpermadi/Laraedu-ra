<?php

namespace App\Filament\Resources\TeacherGradeResource\Pages;

use App\Filament\Resources\TeacherGradeResource;
use App\Models\Teacher;
use App\Models\Userable;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateTeacherGrade extends CreateRecord
{
    protected static string $resource = TeacherGradeResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $teacher = Teacher::find($data['teacher_id'])->userable->user;
        $teacher->assignRole('teacher_grade');

        return static::getModel()::create($data);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
