<?php

namespace App\Filament\Resources\ProjectTargetResource\Pages;

use App\Filament\Resources\ProjectTargetResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProjectTarget extends EditRecord
{
    protected static string $resource = ProjectTargetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
