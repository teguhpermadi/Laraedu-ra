<?php

namespace App\Filament\Resources\ExtracurricularResource\Pages;

use App\Filament\Resources\ExtracurricularResource;
use App\Imports\ExtracurricularImport;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ManageRecords;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Maatwebsite\Excel\Facades\Excel;

class ManageExtracurriculars extends ManageRecords
{
    protected static string $resource = ExtracurricularResource::class;

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
                                return 'ekstrakurikuler.'. $file->getClientOriginalExtension();
                            }
                        )
                        ->required()
            ])
            ->action(function(array $data){
                // dd($data->);
                Excel::import(new ExtracurricularImport, storage_path('/app/public/'.$data['file']) );
            })
            ->extraModalFooterActions([
                Action::make('Download Template Excel')
                    ->color('success')
                    ->action(function () {
                        return response()->download(storage_path('/app/public/templates/ekstrakurikuler.xlsx'));
                    }),
            ])
        ];
    }
}
