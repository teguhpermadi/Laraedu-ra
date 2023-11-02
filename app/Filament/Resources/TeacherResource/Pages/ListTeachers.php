<?php

namespace App\Filament\Resources\TeacherResource\Pages;

use App\Filament\Resources\TeacherResource;
use App\Imports\TeacherImport;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Maatwebsite\Excel\Facades\Excel;

class ListTeachers extends ListRecords
{
    protected static string $resource = TeacherResource::class;

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
                                return 'guru.'. $file->getClientOriginalExtension();
                            }
                        )
                        ->required()
                ])
                ->action(function($data){
                    Excel::import(new TeacherImport, storage_path('/app/public/'.$data['file']) );
                })
                ->extraModalFooterActions([
                    Action::make('Download Template Excel')
                        ->color('success')
                        ->action(function () {
                            return response()->download(storage_path('/app/public/templates/guru.xlsx'));
                        }),
                ])
        ];
    }
}
