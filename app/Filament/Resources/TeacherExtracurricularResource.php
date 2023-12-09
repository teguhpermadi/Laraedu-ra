<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherExtracurricularResource\Pages;
use App\Filament\Resources\TeacherExtracurricularResource\RelationManagers;
use App\Models\AcademicYear;
use App\Models\Extracurricular;
use App\Models\Teacher;
use App\Models\TeacherExtracurricular;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TeacherExtracurricularResource extends Resource
{
    protected static ?int $navigationSort = 4;
    
    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $model = TeacherExtracurricular::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('academic_year_id')->default(AcademicYear::active()->first()->id),
                Select::make('teacher_id')
                    ->options(Teacher::pluck('name', 'id'))
                    ->required(),
                Select::make('extracurricular_id')
                    ->options(Extracurricular::pluck('name', 'id'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('teacher.name'),
                TextColumn::make('extracurricular.name'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                // ->before(function (TeacherExtracurricular $post) {
                //     $teacher = Teacher::find($post->teacher_id)
                //     ->userable
                //     ->user;

                //     $teacher->revokePermissionTo('assesment_student::extracurricular');
                //     $teacher->revokePermissionTo('view_any_student::extracurricular');
                // }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTeacherExtracurriculars::route('/'),
        ];
    }    
}
