<?php

namespace App\Filament\Resources\StudentResource\RelationManagers;

use App\Models\AcademicYear;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GradesRelationManager extends RelationManager
{
    protected static string $relationship = 'studentGrade';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('academic_id')
                    ->default(AcademicYear::where('active', 1)->first()->id),
                Select::make('grade_id')
                    ->relationship('grade', 'name')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('academic.year'),
                Tables\Columns\TextColumn::make('academic.semester'),
                Tables\Columns\TextColumn::make('grade.name'),
                Tables\Columns\TextColumn::make('grade.grade'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
}
