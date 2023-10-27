<?php

namespace App\Filament\Resources\TeacherGradeResource\Pages;

use App\Filament\Resources\TeacherGradeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTeacherGrade extends ViewRecord
{
    protected static string $resource = TeacherGradeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
