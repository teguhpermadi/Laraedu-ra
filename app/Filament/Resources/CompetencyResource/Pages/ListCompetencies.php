<?php

namespace App\Filament\Resources\CompetencyResource\Pages;

use App\Filament\Resources\CompetencyResource;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListCompetencies extends ListRecords
{
    protected static string $resource = CompetencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
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
                    });
            }
        } else {
            $tabs = [
                '-' => Tab::make()
                    ->icon('heroicon-m-x-mark')
                    ->modifyQueryUsing(function(Builder $query){
                        $query->where('student_id', 0);
                    })
            ];
        }
        return $tabs;
    }
}
