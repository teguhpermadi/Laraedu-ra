<?php

namespace App\Filament\Resources\Admin\AcademicYearResource\Pages;

use App\Filament\Resources\Admin\AcademicYearResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAcademicYears extends ListRecords
{
    protected static string $resource = AcademicYearResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
