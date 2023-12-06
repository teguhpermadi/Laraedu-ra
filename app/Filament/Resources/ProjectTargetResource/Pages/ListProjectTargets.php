<?php

namespace App\Filament\Resources\ProjectTargetResource\Pages;

use App\Filament\Resources\ProjectTargetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProjectTargets extends ListRecords
{
    protected static string $resource = ProjectTargetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
