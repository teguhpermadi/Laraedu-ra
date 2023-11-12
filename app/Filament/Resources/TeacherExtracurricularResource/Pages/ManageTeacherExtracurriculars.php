<?php

namespace App\Filament\Resources\TeacherExtracurricularResource\Pages;

use App\Filament\Resources\TeacherExtracurricularResource;
use App\Models\Teacher;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Model;

class ManageTeacherExtracurriculars extends ManageRecords
{
    protected static string $resource = TeacherExtracurricularResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->using(function (array $data, string $model): Model {
                Teacher::find($data['teacher_id'])
                ->userable
                ->user
                ->givePermissionTo(
                    'assesment_student::extracurricular',
                    'view_any_student::extracurricular',
                );


                return $model::create($data);

            }),
        ];
    }
}
