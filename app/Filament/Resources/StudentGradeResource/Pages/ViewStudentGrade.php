<?php

namespace App\Filament\Resources\StudentGradeResource\Pages;

use App\Filament\Resources\StudentGradeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewStudentGrade extends ViewRecord
{
    protected static string $resource = StudentGradeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
