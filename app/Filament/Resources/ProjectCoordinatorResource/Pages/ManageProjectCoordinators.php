<?php

namespace App\Filament\Resources\ProjectCoordinatorResource\Pages;

use App\Filament\Resources\ProjectCoordinatorResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageProjectCoordinators extends ManageRecords
{
    protected static string $resource = ProjectCoordinatorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
