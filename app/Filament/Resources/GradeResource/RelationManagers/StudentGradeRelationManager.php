<?php

namespace App\Filament\Resources\GradeResource\RelationManagers;

use App\Models\AcademicYear;
use App\Models\Grade;
use App\Models\Student;
use App\Models\StudentGrade;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;

class StudentGradeRelationManager extends RelationManager
{
    protected static string $relationship = 'studentGrade';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\TextInput::make('name')
                //     ->required()
                //     ->maxLength(255),
                // Select::make('student_id')
                //     ->options(Student::pluck('name', 'id'))
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('student.name'),
                Tables\Columns\TextColumn::make('student.gender'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->form([
                        Select::make('grade_id')
                            ->options(Grade::pluck('name', 'id'))
                            ->required(),
                        Select::make('student_ids')
                            ->multiple()
                            ->options(Student::whereDoesntHave('studentGrade')->get()->pluck('name', 'id'))
                    ])
                    ->using(function (array $data, string $model): Model {
                        $student_grade = [];
                        foreach ($data['student_ids'] as $student_id) {
                            $student_grade = [
                                'academic_year_id' => AcademicYear::active()->first()->id,
                                'grade_id' => $data['grade_id'],
                                'student_id' => $student_id,
                            ];
                
                            StudentGrade::updateOrCreate($student_grade);
                        }
                        // dd($student_grade);
                        return StudentGrade::updateOrCreate($student_grade);
                    }),

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
