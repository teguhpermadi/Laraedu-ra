<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use App\Filament\Resources\AttendanceResource;
use App\Imports\AttendanceImport;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('upload')
            ->form([
                FileUpload::make('file')
                ->required()
                ->directory('uploads')
                ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/x-excel']),
            ])
            ->action(function($data){
                Excel::import(new AttendanceImport, storage_path('/app/public/'.$data['file']));

                Notification::make()
                ->title('Uploaded successfully')
                ->success()
                ->send();
            }),
            Action::make('download')
            ->url(function(){
                $grade_id = auth()->user()->userable->userable->teacherGrade->grade_id;
                return route('export.attendance', ['grade_id' => $grade_id]);
            }),
        ];
    }
}
