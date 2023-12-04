<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherGradeResource\Pages;
use App\Filament\Resources\TeacherGradeResource\RelationManagers;
use App\Models\AcademicYear;
use App\Models\Grade;
use App\Models\Teacher;
use App\Models\TeacherGrade;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TeacherGradeResource extends Resource
{
    protected static ?int $navigationSort = 3;
    
    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $model = TeacherGrade::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationBadge(): ?string
    {
        return TeacherGrade::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('academic_year_id')->default(AcademicYear::active()->first()->id),
                Select::make('teacher_id')
                    ->options(
                        Teacher::whereDoesntHave('teacherGrade')->pluck('name', 'id')
                    )
                    ->required(),
                Select::make('grade_id')
                    ->options(
                        Grade::whereDoesntHave('teacherGrade')->pluck('name', 'id')
                    )
                    ->required(),
                Select::make('curriculum')
                    ->options([
                        'merdeka' => 'Kurikulum Merdeka',
                        '2013' => 'Kurikulum 2013',
                    ])
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('teacher.name')->searchable(),
                TextColumn::make('grade.name')->searchable(),
                SelectColumn::make('curriculum')
                ->options([
                    'merdeka' => 'Kurikulum Merdeka',
                    '2013' => 'Kurikulum 2013',
                ])
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                ->before(function (TeacherGrade $post) {
                    $teacher = Teacher::find($post->teacher_id)
                    ->userable
                    ->user;

                    $teacher->removeRole('teacher_grade');
                }),
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
            'index' => Pages\ListTeacherGrades::route('/'),
            'create' => Pages\CreateTeacherGrade::route('/create'),
            'view' => Pages\ViewTeacherGrade::route('/{record}'),
            'edit' => Pages\EditTeacherGrade::route('/{record}/edit'),
        ];
    }    
}
