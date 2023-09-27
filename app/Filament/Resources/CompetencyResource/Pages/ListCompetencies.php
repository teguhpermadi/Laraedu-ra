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
            $teacher = Teacher::with('subjects')->find(4);
            $subjects = $teacher->subjects;

            foreach ($subjects as $subject) {
                $data[$subject->id] = Tab::make($subject->code)
                                        ->modifyQueryUsing(function($query) use ($subject) {
                                            $data = TeacherSubject::where([
                                                'teacher_id' => auth()->user()->userable->userable_id,
                                                'subject_id' => $subject->id,
                                            ])->pluck('id');

                                            return $query->whereIn('teacher_subject_id', $data);
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
