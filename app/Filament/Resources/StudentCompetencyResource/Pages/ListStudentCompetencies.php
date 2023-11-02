<?php

namespace App\Filament\Resources\StudentCompetencyResource\Pages;

use App\Filament\Resources\StudentCompetencyResource;
use App\Models\TeacherSubject;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListStudentCompetencies extends ListRecords
{
    protected static string $resource = StudentCompetencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
            Action::make('evaluation')
                ->url(route('filament.admin.resources.student-competencies.evaluation')),
            Action::make('download')
                ->form([
                    Select::make('teacher_subject_id')
                        ->options(
                            TeacherSubject::mySubject()->with('grade')->get()->map(function ($item) {
                                return [
                                    'id' => $item->id,
                                    'name' => $item->subject->name . ' - ' . $item->grade->name,
                                ];
                            })->pluck('name', 'id')
                        )
                        ->required()
                ])->action(function($data){
                    // dd($data['teacher_subject_id']);
                    return to_route('studentCompetencyExcel.getData', ['teacher_subject_id' => $data['teacher_subject_id']]);                    
                }),
        ];
    }

    public function getTabs(): array
    {
        $subjects = TeacherSubject::with('competencies', 'grade')->mySubject();
        $tabs = [];
        if($subjects->count() != 0){
            foreach ($subjects->get() as $subject) {
                $tabs[$subject->id] = Tab::make($subject->subject->code.'-'.$subject->grade->name)
                    ->modifyQueryUsing(function(Builder $query) use ($subject){
                        $competencyId = $subject->competencies->pluck('id');
                        $studentId = $subject->grade->studentGrade->pluck('student_id');
                        $query->whereIn('competency_id',$competencyId)
                            ->whereIn('student_id', $studentId)
                            ->orderBy('student_id');
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
