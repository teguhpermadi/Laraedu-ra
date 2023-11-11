<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentExtracurricularResource\Pages;
use App\Filament\Resources\StudentExtracurricularResource\RelationManagers;
use App\Models\AcademicYear;
use App\Models\Extracurricular;
use App\Models\Student;
use App\Models\StudentExtracurricular;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentExtracurricularResource extends Resource
{
    protected static ?string $model = StudentExtracurricular::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('extracurricular_id')
                    ->options(Extracurricular::pluck('name', 'id'))
                    ->required(),
                Select::make('student_ids')
                    ->options(Student::pluck('name', 'id'))
                    ->multiple()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.name')->searchable(),
                TextColumn::make('extracurricular.name'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListStudentExtracurriculars::route('/'),
            'create' => Pages\CreateStudentExtracurricular::route('/create'),
            'view' => Pages\ViewStudentExtracurricular::route('/{record}'),
            'edit' => Pages\EditStudentExtracurricular::route('/{record}/edit'),
        ];
    }    
}
