<?php

namespace App\Filament\Resources\ExamResource\Pages;

use App\Filament\Resources\ExamResource;
use App\Models\TeacherSubject;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListExams extends ListRecords
{
    protected static string $resource = ExamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
            Action::make('Exam Evaluation')
            ->url(route('filament.admin.resources.exams.evaluation')),
        ];
    }

    // public function getTabs(): array
    // {
    //     $subjects = TeacherSubject::with('exam', 'grade')->mySubject();
    //     $tabs = [];
    //     if($subjects->count() != 0){
    //         foreach ($subjects->get() as $subject) {
    //             $tabs[$subject->id] = Tab::make($subject->subject->code.'-'.$subject->grade->name)
    //                 ->modifyQueryUsing(function(Builder $query) use ($subject){
    //                     $studentId = $subject->grade->studentGrade->pluck('student_id');
    //                     $query->whereIn('student_id', $studentId)
    //                         ->where('category', 'middle')
    //                         ->orderBy('student_id');
    //                 });
    //         }
    //     } else {
    //         $tabs = [
    //             '-' => Tab::make()
    //                 ->icon('heroicon-m-x-mark')
    //                 ->modifyQueryUsing(function(Builder $query){
    //                     $query->where('student_id', 0);
    //                 })
    //         ];
    //     }
    //     return $tabs;
    // }
}
