<?php

namespace App\Filament\Resources\CompetencyResource\Pages;

use App\Filament\Resources\CompetencyResource;
use App\Models\TeacherSubject;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCompetency extends ViewRecord
{
    protected static string $resource = CompetencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $teacher_subject = TeacherSubject::find($data['teacher_subject_id']);
        
        $data['grade_id'] = $teacher_subject->grade_id;
        $data['subject_id'] = $teacher_subject->subject_id;
        return $data;
    }
}
