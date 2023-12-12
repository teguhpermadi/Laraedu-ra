<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherSubjectResource\Pages;
use App\Filament\Resources\TeacherSubjectResource\RelationManagers;
use App\Models\AcademicYear;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class TeacherSubjectResource extends Resource
{
    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $model = TeacherSubject::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationBadge(): ?string
    {
        return TeacherSubject::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('academic_year_id')->default(AcademicYear::active()->first()->id),
                Select::make('teacher_id')->options(Teacher::pluck('name', 'id'))->unique(modifyRuleUsing:function(Unique $rule, callable $get){
                    return $rule->where('academic_year_id', $get('academic_year_id'))
                            ->where('teacher_id', $get('teacher_id'))
                            ->where('subject_id', $get('subject_id'))
                            ->where('grade_id', $get('grade_id'));
                })
                ->required(),
                Select::make('subject_id')->options(Subject::pluck('name', 'id'))->unique(modifyRuleUsing:function(Unique $rule, callable $get){
                    return $rule->where('academic_year_id', $get('academic_year_id'))
                            ->where('teacher_id', $get('teacher_id'))
                            ->where('subject_id', $get('subject_id'))
                            ->where('grade_id', $get('grade_id'));
                })
                ->required(),
                Select::make('grade_id')->options(Grade::pluck('name', 'id'))->unique(modifyRuleUsing:function(Unique $rule, callable $get){
                    return $rule->where('academic_year_id', $get('academic_year_id'))
                            ->where('teacher_id', $get('teacher_id'))
                            ->where('subject_id', $get('subject_id'))
                            ->where('grade_id', $get('grade_id'));
                })
                // ->multiple()
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('teacher.name')->searchable(),
                TextColumn::make('subject.name')->searchable(),
                TextColumn::make('grade.name')->searchable(),
            ])
            ->filters([
                
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                // Action::make('Evaluation')->url(function(TeacherSubject $record){
                //     return route('filament.admin.resources.student-competencies.evaluation', $record);
                // }),
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
            'index' => Pages\ListTeacherSubjects::route('/'),
            'create' => Pages\CreateTeacherSubject::route('/create'),
            'view' => Pages\ViewTeacherSubject::route('/{record}'),
            'edit' => Pages\EditTeacherSubject::route('/{record}/edit'),
        ];
    }    
}
