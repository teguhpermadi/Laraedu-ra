<?php

namespace App\Filament\Resources\TeacherExtracurricularResource\Pages;

use App\Filament\Resources\TeacherExtracurricularResource;
use App\Imports\TeacherExtracurricularImport;
use App\Models\Teacher;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Model;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Maatwebsite\Excel\Facades\Excel;

class ManageTeacherExtracurriculars extends ManageRecords
{
    protected static string $resource = TeacherExtracurricularResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            // ->using(function (array $data, string $model): Model {
            //     Teacher::find($data['teacher_id'])
            //     ->userable
            //     ->user
            //     ->assginRole('teacher_extracurricular');
            //     return $model::create($data);
            // }),
            Action::make('upload')
            ->form([
                FileUpload::make('file')
                ->directory('uploads')
                ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/x-excel'])
                ->getUploadedFileNameForStorageUsing(
                    function (TemporaryUploadedFile $file){
                        return 'teacherExtracurricular.'. $file->getClientOriginalExtension();
                    }
                )
                ->required()      
            ])
            ->action(function($data){
                Excel::import(new TeacherExtracurricularImport, storage_path('/app/public/'.$data['file']) );
            })
            ->extraModalFooterActions([
                Action::make('Download Template Excel')
                    ->color('success')
                    ->url(route('export.teacherExtracurricular')),
            ])
        ];
    }
}
