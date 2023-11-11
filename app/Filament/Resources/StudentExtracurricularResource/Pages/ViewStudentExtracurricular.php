<?php

namespace App\Filament\Resources\StudentExtracurricularResource\Pages;

use App\Filament\Resources\StudentExtracurricularResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewStudentExtracurricular extends ViewRecord
{
    protected static string $resource = StudentExtracurricularResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
