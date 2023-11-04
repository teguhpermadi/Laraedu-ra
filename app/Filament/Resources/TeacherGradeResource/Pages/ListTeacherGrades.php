<?php

namespace App\Filament\Resources\TeacherGradeResource\Pages;

use App\Filament\Resources\TeacherGradeResource;
use App\Imports\TeacherGradeImport;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Maatwebsite\Excel\Facades\Excel;

class ListTeacherGrades extends ListRecords
{
    protected static string $resource = TeacherGradeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('upload')
            ->form([
                FileUpload::make('file')
                ->directory('uploads')
                ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/x-excel'])
                ->getUploadedFileNameForStorageUsing(
                    function (TemporaryUploadedFile $file){
                        return 'teacherGrade.'. $file->getClientOriginalExtension();
                    }
                )
                ->required()    
            ])
            ->action(function($data){
                Excel::import(new TeacherGradeImport, storage_path('/app/public/'.$data['file']) );
            })
            ->extraModalFooterActions([
                Action::make('Download Template Excel')
                    ->color('success')
                    ->url(route('export.teacherGrade')),
            ])
        ];
    }
}
