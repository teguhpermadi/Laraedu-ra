<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Filament\Resources\StudentResource\RelationManagers\DataStudentRelationManager;
use App\Filament\Resources\StudentResource\RelationManagers\GradesRelationManager;
use App\Models\Student;
use App\Models\User;
use App\Models\Userable;
use DateTime;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Filament\Infolists;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

class StudentResource extends Resource
{
    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = 'Database';

    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nisn')->required(),
                TextInput::make('nis')->required(),
                TextInput::make('name')->required(),
                TextInput::make('city_born')->required(),
                DatePicker::make('birthday')->required(),
                Select::make('gender')->options(['laki-laki'=>'Laki-laki', 'perempuan'=>'Perempuan'])->required(),
                Select::make('active')->boolean()->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('nick_name')->searchable(),
                TextColumn::make('gender'),
                IconColumn::make('active')->boolean(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                // Action::make('Userable')->action(function (Student $record){
                //     $userable = Userable::where([
                //         'userable_id' => $record->id,
                //         'userable_type' => 'Student'
                //     ])->count();

                //     if($userable == 0){
                //         $user = User::create([
                //                     'name' => $record->name,
                //                     'email' => Str::slug($record->name).'@student.com',
                //                     'password' => Hash::make('password'),
                //                 ]);
                        
                //         $user->assignRole('student');

                //         Userable::create([
                //             'user_id' => $user->id,
                //             'userable_id' => $record->id,
                //             'userable_type' => Student::class,
                //         ]);

                //         Notification::make()->title('User berhasil dibuat')->success()->send();
                //     } else {
                //         Notification::make()->title('User sudah dibuat')->warning()->send();

                //     }

                // })
                //     ->icon('heroicon-m-user-circle')
                //     ->hidden(fn (Student $record) => $record->hasUserable())
                //     ->visible(auth()->user()->hasPermissionTo('userable Student')), // Action akan tersembunyi jika guru memiliki Userable
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
            DataStudentRelationManager::class,
            GradesRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'view' => Pages\ViewStudent::route('/{record}'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }    

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Identitas')
                ->columns([
                    'sm' => 1,
                    'md' => 2,
                    'xl' => 4,
                    '2xl' => 6,
                ])
                ->schema([
                    TextEntry::make('nisn'),
                    TextEntry::make('nis'),
                    TextEntry::make('name'),
                    TextEntry::make('nick_name'),
                    TextEntry::make('gender'),
                    TextEntry::make('city_born'),
                    TextEntry::make('birthday'),
                    TextEntry::make('dataStudent.religion')->label('Religion'),
                ]),
                Section::make('Data')
                ->columns([
                    'sm' => 1,
                    'md' => 2,
                    'xl' => 4,
                    '2xl' => 6,
                ])
                ->schema([
                    TextEntry::make('dataStudent.previous_school')->label('Previous School'),
                    TextEntry::make('dataStudent.parent_address')->label('Parent Address'),
                    TextEntry::make('dataStudent.date_received')->label('Date Received'),
                    TextEntry::make('dataStudent.grade_received')->label('Grade Received'),
                ]),
                Section::make('Father')
                ->columns([
                    'sm' => 1,
                    'md' => 2,
                    'xl' => 4,
                    '2xl' => 6,
                ])
                ->schema([
                    TextEntry::make('dataStudent.father_name')->label('Father Name'),
                    TextEntry::make('dataStudent.father_education')->label('Father Education'),
                    TextEntry::make('dataStudent.father_occupation')->label('Father Occupation'),
                    TextEntry::make('dataStudent.father_phone')->label('Father Phone'),
                ]),
                Section::make('Mother')
                ->columns([
                    'sm' => 1,
                    'md' => 2,
                    'xl' => 4,
                    '2xl' => 6,
                ])
                ->schema([
                    TextEntry::make('dataStudent.mother_name')->label('mother Name'),
                    TextEntry::make('dataStudent.mother_education')->label('mother Education'),
                    TextEntry::make('dataStudent.mother_occupation')->label('mother Occupation'),
                    TextEntry::make('dataStudent.mother_phone')->label('mother Phone'),
                ]),
                Section::make('Guardian')
                ->columns([
                    'sm' => 1,
                    'md' => 2,
                    'xl' => 4,
                    '2xl' => 6,
                ])
                ->schema([
                    TextEntry::make('dataStudent.guardian_name')->label('guardian Name'),
                    TextEntry::make('dataStudent.guardian_education')->label('guardian Education'),
                    TextEntry::make('dataStudent.guardian_occupation')->label('guardian Occupation'),
                    TextEntry::make('dataStudent.guardian_phone')->label('guardian Phone'),
                ]),
            ]);
    }
}
