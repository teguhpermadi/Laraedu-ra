<?php

namespace App\Filament\Resources\ExamResource\Pages;

use App\Filament\Resources\ExamResource;
use App\Models\Exam;
use App\Models\TeacherSubject;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;

class ExamEvaluation extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = ExamResource::class;

    protected static string $view = 'filament.resources.exam-resource.pages.exam-evaluation';

    public $scores = [];
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
                ->schema([
                    Select::make('teacher_subject')
                        ->options(
                            TeacherSubject::mySubject()->with('grade')->orderBy('subject_id')->get()->map(function ($item) {
                                return [
                                    'id' => $item->id,
                                    'name' => $item->subject->name . ' - ' . $item->grade->name,
                                ];
                            })->pluck('name', 'id')
                        )
                        ->afterStateUpdated(function(callable $set){
                            $set('category', null);
                            $set('scores', null);
                        })
                        ->reactive(),
                    Select::make('category')
                    ->options([
                        'middle' => 'Tengah Semester',
                        'last' => 'Akhir Semester',
                    ])
                    ->afterStateUpdated(function(callable $get, callable $set){
                        $set('scores', null);
                        $teacherSubject = TeacherSubject::with([
                            // 'studentGrade.student',
                            'studentGrade.student.examEvaluation' => function($query) use ($get){
                                $query->where('category', $get('category'));
                                $query->first();
                            },
                            ])->find($get('teacher_subject'));

                        $students = $teacherSubject->studentGrade;

                        $resultArray = [];
                        foreach ($students as $student) {
                            $resultArray[] = [
                                'teacher_subject_id' => $get('teacher_subject'),
                                'student_id' => $student->student_id,
                                'student_name' => $student->student->name,
                                'category_exam' => $get('category'),
                                'score' => ($student->student->examEvaluation) ? $student->student->examEvaluation->score : 0,
                            ];
                        }

                        $this->data['scores'] = $resultArray;
                    })
                    ->reactive()
                ]),
                Section::make('student list')
                ->schema([
                    TableRepeater::make('scores')
                    ->schema([
                        Hidden::make('teacher_subject_id'),
                        Hidden::make('student_id'),
                        Hidden::make('category_exam'),
                        TextInput::make('student_name')
                            ->readOnly(true),
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
        $scores = $this->form->getState()['scores'];
        // Fungsi callback untuk menghapus "student_name" dari setiap elemen
        $filteredScores = array_map(function ($score) {
            unset($score['student_name']);
            return $score;
        }, $scores);

        // dd($filteredScores);
        foreach ($filteredScores as $scoreData) {
            $teacher_subject_id = $scoreData['teacher_subject_id'];
            $studentId = $scoreData['student_id'];
            $category = $scoreData['category_exam'];
            $scoreValue = $scoreData['score'];
        
            // Gunakan updateOrInsert untuk memperbarui atau menyisipkan data
            Exam::updateOrInsert(
                ['student_id' => $studentId, 'category' => $category, 'teacher_subject_id' => $teacher_subject_id],
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
