<?php

namespace App\Filament\Resources\Admin\AcademicYearResource\Pages;

use App\Filament\Resources\Admin\AcademicYearResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAcademicYear extends ViewRecord
{
    protected static string $resource = AcademicYearResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
