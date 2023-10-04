<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompetencyResource\Pages;
use App\Filament\Resources\CompetencyResource\RelationManagers;
use App\Models\Competency;
use App\Models\TeacherSubject;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompetencyResource extends Resource
{
    public $activeTab;

    protected static ?string $model = Competency::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('identity')->label('Identity')
                    ->schema([
                        Select::make('grade_id')->options(function(callable $get, callable $set){
                            $data = TeacherSubject::myGrade()->get()->pluck('grade.name', 'grade.id');
                            return $data;
        
                        })->afterStateUpdated(function ($state, callable $get, callable $set){
                            $set('subject_id', null);
                            $set('teacher_subject_id', null);

                        })
                        ->reactive()
                        ->required(),
                        Select::make('subject_id')->options(function(callable $get, callable $set){
                            if($get('grade_id')){
                                $data = TeacherSubject::mySubjectByGrade($get('grade_id'))->get()->pluck('subject.code', 'subject.id');
                                
                                return $data;
                            }
                            return [];
        
                        })->afterStateUpdated(function ($state, callable $get, callable $set){
                            $data = TeacherSubject::where('grade_id', $get('grade_id'))
                                ->where('teacher_id', auth()->user()->userable->userable_id)
                                ->where('subject_id', $get('subject_id'))->first();
                            $set('teacher_subject_id', $data->id);
                        })
                        ->reactive()
                        ->required(),
                        TextInput::make('passing_grade')->numeric(),
                ])
                ->columns(3),
                
                Hidden::make('teacher_subject_id')->required(),
                Textarea::make('description')->required()->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('teacherSubject.subject.code'),
                TextColumn::make('teacherSubject.grade.grade'),
                TextColumn::make('teacherSubject.grade.name'),
                TextColumn::make('description')->wrap(),
                TextColumn::make('passing_grade'),
            ])
            ->groups([
                'teacherSubject.grade.name',
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListCompetencies::route('/'),
            'create' => Pages\CreateCompetency::route('/create'),
            'view' => Pages\ViewCompetency::route('/{record}'),
            'edit' => Pages\EditCompetency::route('/{record}/edit'),
        ];
    }    

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
