<?php

namespace App\Filament\Resources\StudentCompetencyResource\Pages;

use App\Filament\Resources\StudentCompetencyResource;
use App\Models\Competency;
use App\Models\StudentCompetency;
use App\Models\TeacherSubject;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class Assesment extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static string $resource = StudentCompetencyResource::class;

    protected static string $view = 'filament.resources.student-competency-resource.pages.assesment';

    public $visible = false;
    public $teacher_subject_id = -1;
    public $competency_id = -1;

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
                            ->afterStateUpdated(function(callable $get, callable $set){
                                $set('competency_id', null);
                                $set('scores', null);
                                
                                $teacherSubject = TeacherSubject::with('teacherGrade')->find($get('teacher_subject_id'));
                                if($teacherSubject->teacherGrade->curriculum == '2013'){
                                    $this->visible = true;
                                } else {
                                    $this->visible = false;
                                }

                            })
                            ->live()
                            ->required()
                            ->reactive(),
                        Radio::make('competency_id')
                            ->options(function(Get $get){
                                $comptencies = Competency::where('teacher_subject_id', $get('teacher_subject_id'))->pluck('description', 'id');
                                return $comptencies;
                            })
                            ->reactive()
                            ->required(),
                    ]),
            ]);
    }

    public function submit()
    {
        $this->teacher_subject_id = $this->form->getState()['teacher_subject_id'];
        $this->competency_id = $this->form->getState()['competency_id'];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(StudentCompetency::query())
            ->columns([
                TextColumn::make('student.name'),
                // TextInputColumn::make('score'),
                // TextInputColumn::make('score_skill')
                //     ->visible($this->visible),
                SelectColumn::make('score')
                    ->options([
                        '4' => 'Berkembang Sangat Baik',
                        '3' => 'Berkembang Sesuai Harapan',
                        '2' => 'Mulai Berkembang',
                        '1' => 'Belum Berkembang',
                    ]),
                SelectColumn::make('score_skill')
                    ->options([
                        '4' => 'Berkembang Sangat Baik',
                        '3' => 'Berkembang Sesuai Harapan',
                        '2' => 'Mulai Berkembang',
                        '1' => 'Belum Berkembang',
                    ])
                    ->visible($this->visible),
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
                    // TextInput::make('score')
                    //     ->numeric()
                    //     ->minValue(0)
                    //     ->maxValue(100)
                    //     ->required(),
                    // TextInput::make('score_skill')
                    //     ->numeric()
                    //     ->minValue(0)
                    //     ->maxValue(100)
                    //     ->required()
                    //     ->visible($this->visible)
                    Select::make('score')
                        ->options([
                            '4' => 'Berkembang Sangat Baik',
                            '3' => 'Berkembang Sesuai Harapan',
                            '2' => 'Mulai Berkembang',
                            '1' => 'Belum Berkembang',
                        ]),
                    Select::make('score_skill')
                        ->options([
                            '4' => 'Berkembang Sangat Baik',
                            '3' => 'Berkembang Sesuai Harapan',
                            '2' => 'Mulai Berkembang',
                            '1' => 'Belum Berkembang',
                        ])
                        ->visible($this->visible),
                ])
                ->action(function (Collection $records, $data) {
                    if($this->visible){
                        $dataUpdate = [
                            'score' => $data['score'],
                            'score_skill' => $data['score_skill'],
                        ]; 
                    } else {
                        $dataUpdate = [
                            'score' => $data['score'],
                        ];
                    }

                    return $records->each->update($dataUpdate);
                }),
            ])
            ->modifyQueryUsing(function (Builder $query){
                $query->where('teacher_subject_id', $this->teacher_subject_id)
                    ->where('competency_id', $this->competency_id);
            })
            ->paginated(false);
    }
}
