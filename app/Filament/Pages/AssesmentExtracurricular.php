<?php

namespace App\Filament\Pages;

use App\Models\StudentExtracurricular;
use App\Models\TeacherExtracurricular;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class AssesmentExtracurricular extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;
    use HasPageShield;
    
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.assesment-extracurricular';

    public $extracurricular_id = -1;

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
                        Select::make('extracurricular_id')
                            ->options(
                                TeacherExtracurricular::myExtracurricular()->get()->map(function ($item) {
                                    return [
                                        'id' => $item->extracurricular->id,
                                        'name' => $item->extracurricular->name,
                                    ];
                                })->pluck('name', 'id')
                            )
                            ->live()
                            ->required()
                            ->reactive(),
                    ]),
            ]);
    }

    public function submit()
    {
        $this->extracurricular_id = $this->form->getState()['extracurricular_id'];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(StudentExtracurricular::query())
            ->columns([
                TextColumn::make('student.name'),
                SelectColumn::make('score')
                ->options([
                    'A' => 'Amat baik',
                    'B' => 'Baik',
                    'C' => 'Cukup'
                ])
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
                        Select::make('score')
                            ->options([
                                'A' => 'Amat baik',
                                'B' => 'Baik',
                                'C' => 'Cukup'
                            ])
                    ])
                    ->action(function (Collection $records, $data) {
                        $dataUpdate = [
                            'score' => $data['score'],
                        ]; 
                        
                        return $records->each->update($dataUpdate);
                    }),
            ])
            ->modifyQueryUsing(function (Builder $query){
                $query->where('extracurricular_id', $this->extracurricular_id);
            });
    }
}
