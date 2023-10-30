<?php

namespace App\Filament\Resources\StudentCompetencyResource\Pages;

use App\Filament\Resources\StudentCompetencyResource;
use App\Models\Competency;
use App\Models\StudentCompetency;
use App\Models\TeacherSubject;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Closure;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;

class Evaluation extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = StudentCompetencyResource::class;

    protected static string $view = 'filament.resources.student-competency-resource.pages.evaluation';

    public $students = [];
    public $studentId = [];
    public $scores = [];
    public $competencyId, $teacher_subject_id;
    
    public ?array $data = [];

    public function mount()
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('identity')
                    // ->columns([
                    //     'sm' => 3,
                    //     'xl' => 6,
                    //     '2xl' => 8,
                    // ])
                    ->schema([
                        Select::make('teacher_subject_id')
                            ->options(
                                TeacherSubject::mySubject()->with('grade')->get()->map(function ($item) {
                                    return [
                                        'id' => $item->id,
                                        'name' => $item->subject->name . ' - ' . $item->grade->name,
                                    ];
                                })->pluck('name', 'id')
                            )
                            ->afterStateUpdated(function(callable $get, callable $set){
                                $set('competency_id', null);
                                $set('scores', null);
                            })
                            
                            ->reactive(),
                        Select::make('category')->options([
                            'tengah semester' => 'Tengah Semester',
                            'akhir semester' => 'Akhir Semester',
                            'ulangan' => 'Ulangan',
                        ])->required(),
                        Radio::make('competency_id')
                            
                            ->options(function(callable $get){
                                $comptencies = Competency::where('teacher_subject_id', $get('teacher_subject_id'))->pluck('description', 'id');
                                return $comptencies;
                            })
                            ->afterStateUpdated(function(callable $set, callable $get){
                                $set('scores', null);
                                $teacherSubject = TeacherSubject::with([
                                    'grade.studentGrade.student',
                                    'grade.studentGrade.studentCompetency' => function($query) use($get) {
                                        $query->where('competency_id', $get('competency_id'));
                                    }])
                                    ->find($get('teacher_subject_id'));

                                $students = $teacherSubject->grade->studentGrade;

                                $resultArray = [];
                                $competencyId = $get('competency_id');

                                foreach ($students as $student) {
                                    
                                    $resultArray[] = [
                                        'competency_id' => $competencyId,
                                        'student_id' => $student->student_id,
                                        'score' => ($student->studentCompetency) ? $student->studentCompetency->score : 0,
                                        'student_name' => $student->student->name,
                                    ];
                                }

                                $this->data['scores'] = $resultArray;
                            })
                            ->reactive(),
                    ]),
                Section::make('student list')->schema([
                    TableRepeater::make('scores')
                        ->schema([
                            Hidden::make('competency_id'),
                            Hidden::make('student_id'),
                            TextInput::make('student_name')->readOnly(true),
                            // Select::make('score')
                            //     ->options([
                            //         '1' => 'BB',
                            //         '2' => 'MB',
                            //         '3' => 'BSH',
                            //         '4' => 'BSB',
                            //     ])
                            TextInput::make('score')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(100),
                        ])
                        ->columnSpan('full')
                        ->defaultItems(0)
                        // ->reorderable(false)
                        ->deletable(false)
                        ->addable(false),
                ]),
            ])
            ->statePath('data');
    }

    public function submit()
    {
        $teacher_subject_id = $this->form->getState()['teacher_subject_id'];
        $scores = $this->form->getState()['scores'];

        // Fungsi callback untuk menghapus "student_name" dari setiap elemen
        $filteredScores = array_map(function ($score) {
            unset($score['student_name']);
            return $score;
        }, $scores);

        // dd($filteredScores);
        foreach ($filteredScores as $scoreData) {
            $studentId = $scoreData['student_id'];
            $competencyId = $scoreData['competency_id'];
            $scoreValue = $scoreData['score'];
        
            // Gunakan updateOrInsert untuk memperbarui atau menyisipkan data
            StudentCompetency::updateOrInsert(
                ['student_id' => $studentId, 'competency_id' => $competencyId, 'teacher_subject_id' => $teacher_subject_id],
                ['score' => $scoreValue]
            );

        }

        // kirim notifikasi
        Notification::make()
            ->title('Saved successfully')
            ->success()
            ->send();
    }
}
