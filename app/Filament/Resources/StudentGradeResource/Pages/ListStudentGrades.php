<?php

namespace App\Filament\Resources\StudentGradeResource\Pages;

use App\Filament\Resources\StudentGradeResource;
use App\Imports\StudentGradeImport;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Maatwebsite\Excel\Facades\Excel;

class ListStudentGrades extends ListRecords
{
    protected static string $resource = StudentGradeResource::class;

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
                        return 'studentGrade.'. $file->getClientOriginalExtension();
                    }
                )
                ->required()    
            ])
            ->action(function($data){
                Excel::import(new StudentGradeImport, storage_path('/app/public/'.$data['file']) );
            })
            ->extraModalFooterActions([
                Action::make('Download Template Excel')
                    ->color('success')
                    ->url(route('export.studentGrade')),
            ])
        ];
    }
}
