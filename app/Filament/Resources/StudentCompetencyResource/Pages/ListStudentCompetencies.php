<?php

namespace App\Filament\Resources\StudentCompetencyResource\Pages;

use App\Filament\Resources\StudentCompetencyResource;
use App\Models\TeacherSubject;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListStudentCompetencies extends ListRecords
{
    protected static string $resource = StudentCompetencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
            Action::make('evaluation')
                ->url(route('filament.admin.resources.student-competencies.evaluation'))
        ];
    }

    public function getTabs(): array
    {
        $subjects = TeacherSubject::with('competencies')->mySubject()->get();
        $tabs = [];
        foreach ($subjects as $subject) {
            $tabs[$subject->subject->name] = Tab::make()
                ->modifyQueryUsing(function(Builder $query) use ($subject){
                    $competencyId = $subject->competencies->pluck('id');
                    $query->whereIn('competency_id',$competencyId)->orderBy('student_id');
                });
        }
        return $tabs;
    }
}
