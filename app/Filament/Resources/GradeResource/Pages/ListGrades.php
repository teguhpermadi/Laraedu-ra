<?php

namespace App\Filament\Resources\GradeResource\Pages;

use App\Filament\Resources\GradeResource;
use App\Imports\GradeImport;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class ListGrades extends ListRecords
{
    protected static string $resource = GradeResource::class;

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
                                return 'kelas.'. $file->getClientOriginalExtension();
                            }
                        )
                        ->required()
                ])
                ->action(function(array $data){
                    Excel::import(new GradeImport, storage_path('/app/public/'.$data['file']) );
                })
                ->extraModalFooterActions([
                    Action::make('Download Template Excel')
                        ->color('success')
                        ->action(function () {
                            return response()->download(storage_path('/app/public/templates/kelas.xlsx'));
                        }),
                ])
        ];
    }

    // public function getTabs(): array
    // {
    //     if(auth()->user()->userable){
    //         return [
    //             'all_grade' => Tab::make('All Grades'),
    //             'my_grade' => Tab::make('My Grade')
    //                 ->modifyQueryUsing(function($query){
    //                     return $query->teacher();
    //             }),
    //         ];
    //     } else {
    //         return [
    //             'all_grade' => Tab::make('All Grades'),
    //         ];
    //     }
    // }
}
