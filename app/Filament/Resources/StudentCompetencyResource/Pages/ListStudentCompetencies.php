<?php

namespace App\Filament\Resources\StudentCompetencyResource\Pages;

use App\Filament\Resources\StudentCompetencyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStudentCompetencies extends ListRecords
{
    protected static string $resource = StudentCompetencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
