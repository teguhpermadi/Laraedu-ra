<?php

namespace App\Filament\Resources\CompetencyResource\Pages;

use App\Filament\Resources\CompetencyResource;
use App\Imports\CompetencyImport;
use App\Models\Competency;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Maatwebsite\Excel\Facades\Excel;

class ListCompetencies extends ListRecords
{
    protected static string $resource = CompetencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('upload')
                ->form([
                    Select::make('grade_id')->options(function(callable $get, callable $set){
                        $data = TeacherSubject::myGrade()->get()->pluck('grade.name', 'grade.id');
                        return $data;
    
                    })->afterStateUpdated(function ($state, callable $get, callable $set){
                        $set('subject_id', null);
                        $set('teacher_subject_id', null);

                    })
                    ->reactive()
                    ->required(),
                    Select::make('subject_id')->options(function(callable $get, callable $set){
                        if($get('grade_id')){
                            $data = TeacherSubject::mySubjectByGrade($get('grade_id'))->get()->pluck('subject.code', 'subject.id');
                            
                            return $data;
                        }
                        return [];
    
                    })->afterStateUpdated(function ($state, callable $get, callable $set){
                        $data = TeacherSubject::where('grade_id', $get('grade_id'))
                            ->where('teacher_id', auth()->user()->userable->userable_id)
                            ->where('subject_id', $get('subject_id'))->first();
                        $set('teacher_subject_id', $data->id);
                    })
                    ->reactive()
                    ->required(),
                    Hidden::make('teacher_subject_id'),
                    FileUpload::make('file')
                    ->directory('uploads')
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/x-excel'])
                        // ->getUploadedFileNameForStorageUsing(
                        //     function (TemporaryUploadedFile $file){
                        //         return 'kompetensi.'. $file->getClientOriginalExtension();
                        //     }
                        // )
                    // ->required()
                ])
                ->action(function(array $data){
                    $teacher_subject_id = $data['teacher_subject_id'];
                    $competencies = Excel::toCollection(new CompetencyImport, storage_path('/app/public/'.$data['file']));
        
                    $import = [];
                    
                    foreach ($competencies->toArray()[0] as $competency) {
                        $import = [
                            'teacher_subject_id' => $teacher_subject_id,
                            'code' => $competency['kode'],
                            'description' => $competency['deskripsi'],
                            'passing_grade' => $competency['kkm'],
                        ];
                        
                        Competency::create($import);
                    }
                    
                    // dd($import);
                })
                ->extraModalFooterActions([
                    Action::make('Download Template Excel')
                        ->color('success')
                        ->action(function ($data) {
                            return response()->download(storage_path('/app/public/templates/kompetensi.xlsx'));
                            // dd($data);
                        }),
                ]),
        ];
    }

    public function getTabs(): array
    {
        // if(auth()->user()->userable){
        //     $data = [];
        //     $teacherSubject = Teacher::with('teacherSubject.subject', 'teacherSubject.grade', 'teacherSubject.competencies')->find(auth()->user()->userable->userable_id);
            
        //     // Inisialisasi array untuk menyimpan ID kompetensi untuk setiap subjek
        //     $competencyIds = [];

        //     foreach ($teacherSubject->teacherSubject as $teacherSub) {
        //         // Akses subjek (subject) dari setiap teacherSubject
        //         $subject = $teacherSub->subject;
        //         $grade = $teacherSub->grade;

        //         // Ambil ID subjek dan ID kompetensi yang terkait dengan subjek
        //         $subjectId = $subject->id;
        //         $competencies = $teacherSub->competencies->pluck('id');

        //         // Tambahkan ID kompetensi ke dalam array berdasarkan ID subjek
        //         $competencyIds = $competencies;
                
        //         $data[$subject->id] = Tab::make($subject->code.'-'.$grade->name)
        //             ->modifyQueryUsing(function($query) use ($competencyIds){
        //                 $query->whereIn('id', $competencyIds);
        //             });
        //     }
        //     return $data;
        // } else {
        //     return [
        //         'all_subject' => Tab::make('All Subjects'),
        //     ];
        // }

        $subjects = TeacherSubject::with('competencies', 'grade')->mySubject();
        $tabs = [];
        if($subjects->count() != 0){
            foreach ($subjects->get() as $subject) {
                $tabs[$subject->id] = Tab::make($subject->subject->code.'-'.$subject->grade->name)
                    ->modifyQueryUsing(function(Builder $query) use ($subject){
                        $competencyId = $subject->competencies->pluck('id');
                        $query->whereIn('id',$competencyId);
                    })
                    ->badge(function() use ($subject){
                        $competencyId = $subject->competencies->pluck('id');
                        return Competency::whereIn('id',$competencyId)->count();
                    })
                    ->badgeColor('success');
            }
        } else {
            $tabs = [
                '-' => Tab::make()
                    ->icon('heroicon-m-x-mark')
                    // ->modifyQueryUsing(function(Builder $query){
                    //     $query->where('student_id', 0);
                    // })
            ];
        }
        return $tabs;
    }
}
