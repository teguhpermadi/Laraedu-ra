<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Resources\AttendanceResource\RelationManagers;
use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttendanceResource extends Resource
{
    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('academic_year_id')->default(AcademicYear::active()->first()->id),
                Hidden::make('grade_id'),
                Select::make('student_id')
                    ->reactive()
                    ->required()
                    ->options(Student::myStudentGrade()->pluck('name', 'id'))
                    ->afterStateUpdated(function(callable $set, callable $get){
                        $grade_id = Student::find($get('student_id'))->studentGrade->grade_id;
                        $set('grade_id', $grade_id);
                    }),
                TextInput::make('sick')->numeric()->default(0),
                TextInput::make('permission')->numeric()->default(0),
                TextInput::make('absent')->numeric()->default(0),
                TextInput::make('note'),
                TextInput::make('achievement'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.name')->searchable(),
                TextInputColumn::make('sick')->rules(['numeric']),
                TextInputColumn::make('permission')->rules(['numeric']),
                TextInputColumn::make('absent')->rules(['numeric']),
                // TextInputColumn::make('note'),
                // TextColumn::make('achievement'),
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
            ])
            ->modifyQueryUsing(function(Builder $query){
                $query->myGrade();
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
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'view' => Pages\ViewAttendance::route('/{record}'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }    
}
