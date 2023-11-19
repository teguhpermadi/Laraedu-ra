<?php

namespace App\Filament\Resources\StudentExtracurricularResource\Pages;

use App\Filament\Resources\StudentExtracurricularResource;
use App\Imports\StudentExtracurricularImport;
use App\Models\Extracurricular;
use App\Models\StudentExtracurricular;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListStudentExtracurriculars extends ListRecords
{
    protected static string $resource = StudentExtracurricularResource::class;

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
                        return 'studentExtracurricular.'. $file->getClientOriginalExtension();
                    }
                )
                ->required()      
            ])
            ->action(function($data){
                Excel::import(new StudentExtracurricularImport, storage_path('/app/public/'.$data['file']) );
            })
            ->extraModalFooterActions([
                Action::make('Download Template Excel')
                    ->color('success')
                    ->url(route('export.studentExtracurricular')),
            ])
        ];
    }

    public function getTabs(): array
    {
        $extras = Extracurricular::all();
        $data = [];
        foreach ($extras as $extra) {
            $data[$extra->id] = Tab::make($extra->name)
                                    ->modifyQueryUsing(function (Builder $query) use ($extra){
                                        $query->where('extracurricular_id', $extra->id);
                                    })
                                    ->badge(StudentExtracurricular::where('extracurricular_id', $extra->id)->count())
                                    ->badgeColor('success');
        }

        return $data;
    }
    
}
