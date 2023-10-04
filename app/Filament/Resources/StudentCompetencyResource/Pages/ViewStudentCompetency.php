<?php

namespace App\Filament\Resources\StudentCompetencyResource\Pages;

use App\Filament\Resources\StudentCompetencyResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewStudentCompetency extends ViewRecord
{
    protected static string $resource = StudentCompetencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
