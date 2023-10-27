<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Imports\StudentImport;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Maatwebsite\Excel\Facades\Excel;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

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
                                return 'siswa.'. $file->getClientOriginalExtension();
                            }
                        )
                        ->required()
                ])
                ->action(function(array $data){
                    // dd($data->);
                    Excel::import(new StudentImport, storage_path('/app/public/'.$data['file']) );
                })
                ->extraModalFooterActions([
                    Action::make('Download Template Excel')
                        ->color('success')
                        ->action(function () {
                            return response()->download(storage_path('/app/public/downloads/template siswa.xlsx'));
                        }),
                ])
        ];
    }
}
