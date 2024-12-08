<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Imports\StudentImport;
use App\Models\TeacherGrade;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(),
            'my student' => Tab::make()
                ->modifyQueryUsing(function (Builder $query){
                    $user = auth()->user();
                    if($user->userable){
                        $teacher_id = $user->userable->userable_id;
                        $data = TeacherGrade::where('teacher_id', $teacher_id)->myGrade()->first();
                        $students = $data->grade->StudentGrade->pluck('student_id');
                        $query->whereIn('id', $students);
                    } else {
                        return $query->where('id', -1);
                    }
                    
                }),
        ];
    }

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
                    try {
                        Excel::import(new StudentImport, storage_path('/app/public/'.$data['file']) );
                    } catch (\Throwable $th) {
                        Log::error($th->getMessage());
                    }
                })
                ->extraModalFooterActions([
                    Action::make('Download Template Excel')
                        ->color('success')
                        ->action(function () {
                            return response()->download(storage_path('/app/public/templates/siswa.xlsx'));
                        }),
                ])
        ];
    }
}
