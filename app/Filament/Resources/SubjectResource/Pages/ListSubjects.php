<?php

namespace App\Filament\Resources\SubjectResource\Pages;

use App\Filament\Resources\SubjectResource;
use App\Imports\SubjectImport;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Maatwebsite\Excel\Facades\Excel;

class ListSubjects extends ListRecords
{
    protected static string $resource = SubjectResource::class;

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
                                return 'mapel.'. $file->getClientOriginalExtension();
                            }
                        )
                        ->required()
                ])
                ->action(function(array $data){
                    Excel::import(new SubjectImport, storage_path('/app/public/'.$data['file']) );
                })
                ->extraModalFooterActions([
                    Action::make('Download Template Excel')
                        ->color('success')
                        ->action(function () {
                            return response()->download(storage_path('/app/public/templates/mapel.xlsx'));
                        }),
                ])
        ];
    }

    // public function getTabs(): array
    // {
    //     if(auth()->user()->userable){
    //         return [
    //             'all_subject' => Tab::make('All Subjects'),
    //             'my_subject' => Tab::make('My Subject')
    //                 ->modifyQueryUsing(function($query){
    //                     return $query->teacher();
    //             }),
    //         ];
    //     } else {
    //         return [
    //             'all_subject' => Tab::make('All Subjects'),
    //         ];
    //     }
    // }
}
