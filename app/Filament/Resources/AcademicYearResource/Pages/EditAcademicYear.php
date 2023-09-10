<?php

namespace App\Filament\Resources\AcademicYearResource\Pages;

use App\Filament\Resources\AcademicYearResource;
use App\Models\AcademicYear;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditAcademicYear extends EditRecord
{
    protected static string $resource = AcademicYearResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        AcademicYear::setActive($record->id);
        $record->update($data);
    
        return $record;
    }
}
