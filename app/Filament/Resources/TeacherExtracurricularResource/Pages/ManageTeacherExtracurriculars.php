<?php

namespace App\Filament\Resources\TeacherExtracurricularResource\Pages;

use App\Filament\Resources\TeacherExtracurricularResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTeacherExtracurriculars extends ManageRecords
{
    protected static string $resource = TeacherExtracurricularResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
