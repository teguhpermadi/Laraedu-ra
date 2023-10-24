<?php

namespace App\Filament\Resources\CompetencyResource\Pages;

use App\Filament\Resources\CompetencyResource;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;

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
        if(auth()->user()->userable){
            $data = [];
            $teacherSubject = Teacher::with('teacherSubject.subject', 'teacherSubject.grade', 'teacherSubject.competencies')->find(auth()->user()->userable->userable_id);
            
            // Inisialisasi array untuk menyimpan ID kompetensi untuk setiap subjek
            $competencyIds = [];

            foreach ($teacherSubject->teacherSubject as $teacherSub) {
                // Akses subjek (subject) dari setiap teacherSubject
                $subject = $teacherSub->subject;
                $grade = $teacherSub->grade;

                // Ambil ID subjek dan ID kompetensi yang terkait dengan subjek
                $subjectId = $subject->id;
                $competencies = $teacherSub->competencies->pluck('id');

                // Tambahkan ID kompetensi ke dalam array berdasarkan ID subjek
                $competencyIds = $competencies;
                
                $data[$subject->id] = Tab::make($subject->code.'-'.$grade->name)
                    ->modifyQueryUsing(function($query) use ($competencyIds){
                        $query->whereIn('id', $competencyIds);
                    });
            }
            return $data;
        } else {
            return [
                'all_subject' => Tab::make('All Subjects'),
            ];
        }
    }
}
