<?php

namespace App\Filament\Resources\StudentCompetencyResource\Pages;

use App\Filament\Resources\StudentCompetencyResource;
use App\Models\Competency;
use App\Models\TeacherSubject;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Closure;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Page implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static string $resource = StudentCompetencyResource::class;

    protected static string $view = 'filament.resources.student-competency-resource.pages.evaluation';

    public $students = [];
    public $studentId = [];
    public $scores = [];
    public $competencyId, $teacher_subject_id;
    
    public ?array $data = [];

    public function mount($teacher_subject_id)
    {
        $this->teacher_subject_id = $teacher_subject_id;
        $this->form->fill();
    }

    protected function getTableQuery(): Builder 
    {
        return Competency::query()->where('teacher_subject_id', $this->teacher_subject_id);
    } 

    protected function getTableColumns(): array 
    {
        return [
            TextColumn::make('description')->wrap(),
            TextColumn::make('passing_grade'),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('check')
            ->action(function(Model $record){
                $this->competencyClick($record->id);
            }),
        ];
    }

    protected function isTablePaginationEnabled(): bool 
    {
        return false;
    } 

    public function competencyClick($competencyId)
    {
        $teacherSubject = TeacherSubject::with([
                            'grade.studentGrade.student',
                            'grade.studentGrade.studentCompetency' => function($query) use ($competencyId){
                                $query->where('competency_id', $competencyId);
                            }])
                            ->find($this->teacher_subject_id);

        $this->competencyId = $competencyId;
        $this->students = $teacherSubject->grade->studentGrade;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Select::make('teacher_subject_id')
                //     ->options(),
                Select::make('competency_id')
                    ->options(Competency::where('teacher_subject_id', $this->teacher_subject_id)->pluck('description', 'id'))
                    ->afterStateUpdated(function(callable $set, callable $get){
                        $teacherSubject = TeacherSubject::with([
                            'grade.studentGrade.student',
                            'grade.studentGrade.studentCompetency' => function($query) use($get) {
                                $query->where('competency_id', $get('competency_id'));
                            }])
                            ->find($this->teacher_subject_id);

                        $students = $teacherSubject->grade->studentGrade;

                        $resultArray = [];
                        $competencyId = $get('competency_id');

                        foreach ($students as $studentGrade) {
                            $score = 0; // Set nilai awal score menjadi 0
                        
                            if ($studentGrade->studentCompetency && $studentGrade->studentCompetency->score) {
                                $score = $studentGrade->studentCompetency->score;
                            }
                        
                            $resultArray[] = [
                                'competency_id' => $competencyId,
                                'student_id' => $studentGrade->student_id,
                                'score' => ($studentGrade->studentCompetency) ? $studentGrade->studentCompetency->score : 0,
                            ];
                        }

                        $this->data['scores'] = $resultArray;
                    })->reactive(),
                Repeater::make('scores')
                    ->schema([
                        Hidden::make('competency_id'),
                        TextInput::make('student_id'),
                        TextInput::make('score')
                    ])
                    ->defaultItems(0)
                    ->deletable(false)
                    ->addable(false),
            ])
            ->statePath('data');
    }

    public function submit()
    {
        dd($this->form->getState());
    }
}
