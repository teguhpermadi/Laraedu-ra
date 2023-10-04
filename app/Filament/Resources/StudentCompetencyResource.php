<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentCompetencyResource\Pages;
use App\Filament\Resources\StudentCompetencyResource\RelationManagers;
use App\Models\StudentCompetency;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentCompetencyResource extends Resource
{
    protected static ?string $model = StudentCompetency::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.name')->searchable(),
                TextColumn::make('competency.teacherSubject.subject.code'),
                TextColumn::make('competency.teacherSubject.grade.name'),
                TextColumn::make('competency.description')->wrap(),
                TextColumn::make('score'),
                TextColumn::make('result')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'TIDAK LULUS' => 'warning',
                        'LULUS' => 'success',
                    }),
            ])
            ->filters([
                
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->modifyQueryUsing(function(Builder $query){
                $query->result();
            });
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudentCompetencies::route('/'),
            'create' => Pages\CreateStudentCompetency::route('/create'),
            'view' => Pages\ViewStudentCompetency::route('/{record}'),
            'edit' => Pages\EditStudentCompetency::route('/{record}/edit'),
        ];
    }    
}
