<?php

namespace App\Filament\Resources\ExamResource\Pages;

use App\Filament\Resources\ExamResource;
use App\Models\Exam;
use App\Models\TeacherSubject;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Form;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class Evaluation extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;
    
    protected static string $resource = ExamResource::class;

    protected static string $view = 'filament.resources.exam-resource.pages.evaluation';

    public $teacher_subject_id = -1;

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
                        Select::make('teacher_subject_id')
                            ->options(
                                TeacherSubject::mySubject()->with('grade')->orderBy('subject_id')->get()->map(function ($item) {
                                    return [
                                        'id' => $item->id,
                                        'name' => $item->subject->name . ' - ' . $item->grade->name,
                                    ];
                                })->pluck('name', 'id')
                            )
                            // ->afterStateUpdated(function(callable $get, callable $set){
                            //     $set('competency_id', null);
                            //     $set('scores', null);
                                
                            //     $teacherSubject = TeacherSubject::with('teacherGrade')->find($get('teacher_subject_id'));
                            //     if($teacherSubject->teacherGrade->curriculum == '2013'){
                            //         $this->visible = true;
                            //     } else {
                            //         $this->visible = false;
                            //     }

                            // })
                            ->live()
                            ->required()
                            ->reactive(),
                    ]),
            ]);
    }

    public function submit()
    {
        $this->teacher_subject_id = $this->form->getState()['teacher_subject_id'];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Exam::query())
            ->columns([
                TextColumn::make('student.name'),
                TextInputColumn::make('score_middle'),
                TextInputColumn::make('score_last'),
            ])
            ->filters([
                // 
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                BulkAction::make('Scoring')
                ->form([
                    TextInput::make('score_middle')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->required(),
                    TextInput::make('score_last')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->required()
                    
                ])
                ->action(function (Collection $records, $data) {
                    $dataUpdate = [
                        'score_middle' => $data['score_middle'],
                        'score_last' => $data['score_last'],
                    ]; 
                    
                    return $records->each->update($dataUpdate);
                }),
            ])
            ->modifyQueryUsing(function (Builder $query){
                $query->where('teacher_subject_id', $this->teacher_subject_id);
            })
            ->paginated(false);
    }
}
