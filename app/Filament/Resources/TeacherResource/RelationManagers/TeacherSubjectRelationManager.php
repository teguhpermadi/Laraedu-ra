<?php

namespace App\Filament\Resources\TeacherResource\RelationManagers;

use App\Models\Grade;
use App\Models\Subject;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TeacherSubjectRelationManager extends RelationManager
{
    protected static string $relationship = 'teacherSubject';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\TextInput::make('name')
                //     ->required()
                //     ->maxLength(255),
                Select::make('grade_id')->options(Grade::pluck('name', 'id'))->required(),
                Select::make('subject_id')->options(Subject::pluck('name', 'id'))->required(),
                // TextInput::make('passing_grade')->rules('numeric'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('academic.year'),
                Tables\Columns\TextColumn::make('academic.semester'),
                Tables\Columns\TextColumn::make('subject.name'),
                // Tables\Columns\TextColumn::make('subject.code'),
                // Tables\Columns\TextColumn::make('grade.code'),
                Tables\Columns\TextColumn::make('grade.name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    BulkAction::make('Edit mata pelajaran')
                    ->color('warning')
                    ->icon('heroicon-m-pencil-square')
                    ->form([
                        Select::make('subject_id')->options(Subject::pluck('name', 'id'))->required(),
                    ])->action(function(Collection $records, $data){
                        return $records->each->update([
                            'subject_id' => $data['subject_id'],
                        ]);
                    })
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
}
